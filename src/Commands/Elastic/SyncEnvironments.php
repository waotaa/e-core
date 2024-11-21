<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Environment;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncEnvironments extends Command
{
    protected $signature = 'elastic:sync-environments {--f|fresh}';
    protected $description = 'Sync all environments to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing environments');
        $this->output->writeln('');
        $index = 'environments';

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

        /** @var EnvironmentRepositoryInterface $environmentRepo */
        $environmentRepo = app(EnvironmentRepositoryInterface::class);
        $environments = $environmentRepo
            ->getElasticResourceBuilder()
            ->get();

        $this->output->writeln($environments->count() . ' environments found');
        $this->output->writeln('');

        foreach ($environments as $environment) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $environment->name);
            dispatch(new SyncSearchableModelToElasticJob($environment));
        }

        if (!$this->option('fresh')) {
            foreach (Environment::onlyTrashed()->get() as $environment) {
                dispatch(new RemoveResourceFromElasticJob($environment->getSearchIndex(), $environment->getSearchId()));
            }
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing environments finished!');
        return 0;
    }
}
