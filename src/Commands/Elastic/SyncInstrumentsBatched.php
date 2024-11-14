<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Support\Facades\DB;
use Vng\EvaCore\Jobs\SyncBulkResourcesToElasticJob;
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

        $this->call('elastic:delete-index', ['index' => 'instruments', '--force' => true]);

        $index = 'instruments';
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

        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instruments = $instrumentRepository
            ->getElasticResourceBuilder()
            ->get();

        $this->output->writeln($instruments->count() . ' instruments found');
        $this->output->writeln('');

        $delay = 0;
        $instruments->chunk(SyncBulkResourcesToElasticJob::BATCH_SIZE)->each(function ($instrumentsBatch) use ($index, &$delay) {
            $syncAttempt = SyncAttemptFactory::createSyncAttempt(SyncAttempt::ACTION_INDEX);

            dispatch(new SyncBulkResourcesToElasticJob(
                $instrumentsBatch,
                $index,
                Instrument::getResourceClass(),
                $syncAttempt
            ))->delay(now()->addSeconds($delay));

            $delay += 10; // Verhoog de vertraging met 10 seconden voor de volgende iteratie
        });

        $this->output->newLine(2);
        $this->output->writeln('syncing instruments finished!');
        return 0;
    }
}
