<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Support\Facades\Bus;
use Vng\EvaCore\Jobs\FetchNewInstrumentRatingsJob;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncBulkResourcesToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;
use Vng\EvaCore\Services\ElasticSearch\SyncAttemptFactory;

class SyncInstruments extends Command
{
    use UsePrefixedIndex;

    protected $signature = 'elastic:sync-instruments {--f|fresh} {--p|pure}';
    protected $description = 'Sync all instruments to ES';

    public function handle(): int
    {
        $this->output->writeln('syncing instruments...');
        $this->output->writeln('');
        $index = 'instruments';

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => $index, '--force' => true]);
        }

        $prefix = $this->getIndexPrefix();
        if ($prefix) {
            $this->output->writeln("used index-prefix: {$prefix}");
        }
        $fullIndex = $this->prefixIndex($index);
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
            $this->output->write('.');
            $syncAttempt = SyncAttemptFactory::makeSyncAttempt(SyncAttempt::ACTION_INDEX)
                ->setNote('instrument bulk');
            $syncAttempt->save();
            $jobs = [];

            // If not pure, then fetch rating first
            if (!($this->option('fresh') || $this->option('pure'))) {
                foreach ($instrumentsBatch as $instrument) {
                    $jobs[] = new FetchNewInstrumentRatingsJob($instrument);
                }
            }
            $jobs[] = new SyncBulkResourcesToElasticJob(
                $instrumentsBatch,
                $index,
                Instrument::getResourceClass(),
                $syncAttempt
            );

            Bus::chain($jobs)->delay(now()->addSeconds($delay))->dispatch();

            // Verhoog de vertraging met 5 seconden voor de volgende iteratie, maar nooit meer dan 900
            $delay = min($delay + 5, 900);
        });

        if (!$this->option('fresh')) {
            foreach (Instrument::onlyTrashed()->get() as $instrument) {
                $syncAttempt = SyncAttemptFactory::createSyncAttempt(
                    SyncAttempt::ACTION_DELETE,
                    $instrument
                );

                dispatch(new RemoveResourceFromElasticJob(
                    $instrument->getSearchIndex(),
                    $instrument->getSearchId(),
                    $syncAttempt
                ));
            }
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing instruments finished!');
        return 0;
    }
}
