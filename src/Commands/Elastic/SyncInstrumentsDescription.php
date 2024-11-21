<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\ElasticResources\Original\Instrument\InstrumentDescriptionResource;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncBulkResourcesToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchEndpointService;
use Vng\EvaCore\Services\ElasticSearch\SyncAttemptFactory;

class SyncInstrumentsDescription extends Command
{
    protected $signature = 'elastic:sync-instruments-description {--f|fresh}';
    protected $description = 'Sync all instruments public description to ES';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing instruments description');
        $this->output->writeln('');

        if ($this->option('fresh')) {
            $this->call('elastic:delete-index', ['index' => 'instruments_description', '--force' => true]);
        }

        $index = 'instruments_description';
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
            $syncAttempt = SyncAttemptFactory::makeSyncAttempt(SyncAttempt::ACTION_INDEX)
                ->setNote('instrument description');
            $syncAttempt->save();

            dispatch(new SyncBulkResourcesToElasticJob(
                $instrumentsBatch,
                'instruments_description',
                InstrumentDescriptionResource::class,
                $syncAttempt
            ));

            // Verhoog de vertraging met 5 seconden voor de volgende iteratie, maar nooit meer dan 900
            $delay = min($delay + 5, 900);
        });

        foreach (Instrument::onlyTrashed()->get() as $instrument) {
            $syncAttempt = SyncAttemptFactory::makeSyncAttempt(
                SyncAttempt::ACTION_DELETE,
                $instrument
            )
                ->setNote('instrument description');
            $syncAttempt->save();

            dispatch(new RemoveResourceFromElasticJob(
                'instruments_description',
                $instrument->getSearchId(),
                $syncAttempt
            ));
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing instruments description finished!');
        return 0;
    }
}
