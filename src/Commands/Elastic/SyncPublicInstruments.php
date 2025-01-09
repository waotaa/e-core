<?php

namespace Vng\EvaCore\Commands\Elastic;

use Illuminate\Console\Command;
use Vng\EvaCore\ElasticResources\Original\Shared\InstrumentResource;
use Vng\EvaCore\Jobs\ElasticPublic\RemoveResourceFromPublicElasticJob;
use Vng\EvaCore\Jobs\ElasticPublic\SyncBulkResourcesToPublicElasticJob;
use Vng\EvaCore\Jobs\SyncBulkResourcesToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\ElasticSearch\Clients\ElasticPublicClientBuilder;
use Vng\EvaCore\Services\ElasticSearch\SyncAttemptFactory;

class SyncPublicInstruments extends Command
{
    protected $signature = 'elastic:sync-public-instruments {--f|fresh}';
    protected $description = 'Sync public instruments resources to public ES instance';

    public function handle(): int
    {
        $this->getOutput()->writeln('syncing public instruments');
        $this->output->writeln('');
        $index = 'instruments';

        if ($this->option('fresh')) {
            $this->call(DeletePublicIndex::class, ['index' => $index, '--force' => true]);
        }

        if (!ElasticPublicClientBuilder::hasSettings()){
            $this->output->writeln('public instance settings missing');
            return 0;
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
                ->setNote('public instruments');
            $syncAttempt->save();

            dispatch(new SyncBulkResourcesToPublicElasticJob(
                $instrumentsBatch,
                $index,
                InstrumentResource::class,
                $syncAttempt
            ))->delay(now()->addSeconds($delay));

            // Verhoog de vertraging met 5 seconden voor de volgende iteratie, maar nooit meer dan 900
            $delay = min($delay + 5, 900);
        });

        if (!$this->option('fresh')) {
            $this->output->warning('Removing instruments from public instance');
            foreach (Instrument::onlyTrashed()->get() as $instrument) {
                dispatch(new RemoveResourceFromPublicElasticJob(
                    'instruments',
                    $instrument->getSearchId()
                ));
            }
        }

        $this->output->newLine(2);
        $this->output->writeln('syncing public instruments finished!');
        return 0;
    }
}
