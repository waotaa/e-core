<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Models\Township;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\TownshipRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncTownships extends Command
{
    protected $signature = 'elastic:sync-townships {--f|fresh}';
    protected $description = 'Sync townships to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing townships');
        $this->output->writeln('');
        $index = 'townships';

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => $index, '--force' => true]);
        }

        $fullIndex = $index;
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $this->output->writeln("used index-prefix: {$prefix}");
            $fullIndex = $prefix . '-' . $index;
        }
        $this->output->writeln("used index: {$fullIndex}");

        if (!ElasticsearchEndpointService::make()->indexExists($fullIndex)) {
            $this->call(CreateIndex::class, [
                'index' => $index
            ]);
        }

        /** @var TownshipRepositoryInterface $townshipRepository */
        $townshipRepository = app(TownshipRepositoryInterface::class);
        $townships = $townshipRepository
            ->builder()
            ->with([
                'region'
            ])
            ->get();

        $this->output->writeln($townships->count() . ' townships found');
        $this->output->writeln('');

        foreach ($townships as $township) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $township->name);

            $attempt = new SyncAttempt();
            $attempt->action = 'sync';
            $attempt->resource()->associate($township);
            $attempt->save();

            dispatch(new SyncSearchableModelToElasticJob($township, $attempt));
        }

        foreach (Township::onlyTrashed()->get() as $township) {
            dispatch(new RemoveResourceFromElasticJob($township->getSearchIndex(), $township->getSearchId()));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing townships finished!');
        return 0;
    }
}
