<?php

namespace Vng\EvaCore\Listeners;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Bus;
use Vng\EvaCore\ElasticResources\Both\Instrument\InstrumentDescriptionWerknemersdienstverleningResource;
use Vng\EvaCore\Events\InstrumentRemoved;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Jobs\PruneSyncAttempts;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncResourceToElasticJob;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Services\ElasticSearch\SyncAttemptFactory;

class InstrumentEventSubscriber
{
    public function handleInstrumentSaved(InstrumentSaved $event)
    {
        $instrument = $event->instrument;

        $attempt = SyncAttemptFactory::createSyncAttempt(
            SyncAttempt::ACTION_INDEX,
            $instrument
        );
        $attempt->setAttribute('note', 'on instruments_description index');
        $attempt->save();

        Bus::chain([
            new SyncResourceToElasticJob(
                $instrument,
                'instruments_description',
                InstrumentDescriptionWerknemersdienstverleningResource::class,
                $attempt
            ),
            new PruneSyncAttempts()
        ])->dispatch();
    }

    public function handleInstrumentRemoved(InstrumentRemoved $event)
    {
        $instrument = $event->instrument;

        $attempt = SyncAttemptFactory::createSyncAttempt(
            SyncAttempt::ACTION_DELETE,
            $instrument
        );
        $attempt->setAttribute('note', 'on instrumets_description index');
        $attempt->save();

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
