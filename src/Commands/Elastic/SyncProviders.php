<?php

namespace Vng\EvaCore\Commands\Elastic;

use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Provider;
use Illuminate\Console\Command;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncProviders extends Command
{
    protected $signature = 'elastic:sync-providers {--f|fresh}';
    protected $description = 'Sync all providers to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing providers');
        $this->output->writeln('');
        $index = 'providers';

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

        /** @var ProviderRepositoryInterface $providerRepository */
        $providerRepository = app(ProviderRepositoryInterface::class);
        $providers = $providerRepository
            ->getElasticResourceBuilder()
            ->get();

        $this->output->writeln($providers->count() . ' providers found');
        $this->output->writeln('');

        foreach ($providers as $provider) {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $provider->name);
            dispatch(new SyncSearchableModelToElasticJob($provider));
        }

        foreach (Provider::onlyTrashed()->get() as $provider) {
            dispatch(new RemoveResourceFromElasticJob($provider->getSearchIndex(), $provider->getSearchId()));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing providers finished!');
        return 0;
    }
}
