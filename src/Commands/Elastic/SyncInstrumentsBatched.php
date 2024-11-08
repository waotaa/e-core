<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Support\Facades\Bus;
use Vng\EvaCore\Jobs\FetchNewInstrumentRatingsJob;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncBulkResourcesToElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;
use Vng\EvaCore\Services\ElasticSearch\SyncAttemptFactory;

class SyncInstrumentsBatched extends Command
{
    protected $signature = 'elastic:sync-instruments-batched';
    protected $description = 'Sync all instruments to ES in bulk requests';

    public function handle(): int
    {
        $this->output->writeln('bulk syncing instruments...');
        $this->output->writeln('');

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'instruments', '--force' => true]);
        }

        $index = 'instruments';
        $prefix = config('elastic.prefix');
        if ($prefix) {
            $this->output->writeln("used index-prefix: {$prefix}");
            $index = $prefix . '-' . $index;
        }
        $this->output->writeln("used index: {$index}");

        if (!ElasticsearchEndpointService::make()->indexExists($index)) {
            $this->call(CreateIndex::class, [
                'index' => 'instruments'
            ]);
        }

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instruments = $instrumentRepository
            ->getElasticResourceBuilder()
            ->get();

        $this->output->writeln($instruments->count() . ' instruments found');
        $this->output->writeln('');

        $syncAttempt = SyncAttemptFactory::createSyncAttempt(SyncAttempt::ACTION_INDEX);

        new SyncBulkResourcesToElasticJob(
            $instruments,
            $index,
            Instrument::getResourceClass(),
            $syncAttempt
        );

        $this->output->newLine(2);
        $this->output->writeln('syncing instruments finished!');
        return 0;
    }
}
