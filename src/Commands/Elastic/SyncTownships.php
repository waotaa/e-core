<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Models\Township;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\TownshipRepositoryInterface;

class SyncTownships extends Command
{
    protected $signature = 'elastic:sync-townships {--f|fresh}';
    protected $description = 'Sync townships to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing townships');
        $this->getOutput()->writeln('used index-prefix: ' . config('elastic.prefix'));

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'townships', '--force' => true]);
        }

        $this->output->writeln('');

        /** @var TownshipRepositoryInterface $townshipRepository */
        $townshipRepository = app(TownshipRepositoryInterface::class);
        $townships = $townshipRepository
            ->builder()
            ->with([
                'region'
            ])
            ->get();

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

        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
