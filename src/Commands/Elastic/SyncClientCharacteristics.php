<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\ClientCharacteristicRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;

class SyncClientCharacteristics extends Command
{
    protected $signature = 'elastic:sync-client-characteristics {--f|fresh}';
    protected $description = 'Sync all client characteristics to ES';

    public function handle(): int
    {
        $this->output->writeln('syncing client characteristics...');
        $index = 'client_characteristics';
        $this->output->writeln('');

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

        /** @var ClientCharacteristicRepositoryInterface $clientCharacteristicsRepository */
        $clientCharacteristicsRepository = app(ClientCharacteristicRepositoryInterface::class);
        $clientCharacteristics = $clientCharacteristicsRepository
            ->all();

        $this->output->writeln($clientCharacteristics->count() . ' client characteristics found');
        $this->output->writeln('');

        foreach ($clientCharacteristics as $clientCharacteristic) {
            $this->output->write('.');
//            $this->getOutput()->write('- ' . $clientCharacteristic->name);

            $attempt = new SyncAttempt();
            $attempt->action = 'sync';
            $attempt->resource()->associate($clientCharacteristic);
            $attempt->save();

            dispatch(new SyncSearchableModelToElasticJob($clientCharacteristic, $attempt));
        }

        if (!$this->option('fresh')) {
            foreach (ClientCharacteristic::onlyTrashed()->get() as $clientCharacteristic) {
                dispatch(new RemoveResourceFromElasticJob($clientCharacteristic->getSearchIndex(), $clientCharacteristic->getSearchId()));
            }
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing client characteristics finished!');
        return 0;
    }
}

