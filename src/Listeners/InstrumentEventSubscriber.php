<?php

namespace Vng\EvaCore\Listeners;

use Illuminate\Support\Facades\Bus;
use Vng\EvaCore\ElasticResources\Instrument\InstrumentDescriptionResource;
use Vng\EvaCore\Events\InstrumentRemoved;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Jobs\PruneSyncAttempts;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Illuminate\Events\Dispatcher;
use Vng\EvaCore\Jobs\SyncResourceToElasticJob;
use Vng\EvaCore\Services\ElasticSearch\SyncService;

class InstrumentEventSubscriber
{
    public function handleInstrumentSaved(InstrumentSaved $event)
    {
        $instrument = $event->instrument;
        $attempt = SyncService::createSyncAttempt($instrument, 'save_description');

        Bus::chain([
            new SyncResourceToElasticJob(
                $instrument,
                'instruments_description',
                InstrumentDescriptionResource::class,
                $attempt
            ),
            new PruneSyncAttempts()
        ])->dispatch();
    }

    public function handleInstrumentRemoved(InstrumentRemoved $event)
    {
        $instrument = $event->instrument;
        $attempt = SyncService::createSyncAttempt($instrument, 'remove_description');

        Bus::chain([
            new RemoveResourceFromElasticJob(
                'instruments_description',
                $event->instrument->getSearchId(),
                $attempt
            ),
            new PruneSyncAttempts()
        ])->dispatch();
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            InstrumentSaved::class,
            [InstrumentEventSubscriber::class, 'handleInstrumentSaved']
        );

        $events->listen(
            InstrumentRemoved::class,
            [InstrumentEventSubscriber::class, 'handleInstrumentRemoved']
        );
    }
}
