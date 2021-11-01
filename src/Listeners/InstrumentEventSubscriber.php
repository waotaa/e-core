<?php

namespace Vng\EvaCore\Listeners;

use Vng\EvaCore\ElasticResources\Instrument\InstrumentDescriptionResource;
use Vng\EvaCore\Events\InstrumentRemoved;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Jobs\RemoveCustomResourceFromElastic;
use Vng\EvaCore\Jobs\SyncCustomResourceToElastic;
use Vng\EvaCore\Models\SyncAttempt;
use Carbon\Carbon;
use Illuminate\Events\Dispatcher;

class InstrumentEventSubscriber
{
    public function handleInstrumentSaved(InstrumentSaved $event)
    {
        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'save_description';
        $attempt->resource()->associate($event->instrument);
        $attempt->save();

        dispatch(new SyncCustomResourceToElastic(
            $event->instrument,
            'instruments_description',
            InstrumentDescriptionResource::make($event->instrument),
            $attempt
        ));
    }

    public function handleInstrumentRemoved(InstrumentRemoved $event)
    {
        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'remove_description';
        $attempt->resource()->associate($event->instrument);
        $attempt->save();

        dispatch(new RemoveCustomResourceFromElastic(
            $event->instrument,
            'instruments_description',
            $attempt
        ));
    }

    private function clearOldAttempts()
    {
        $weekAgo = Carbon::now()->subDays(7)->startOfDay()->toDateTimeString();
        SyncAttempt::query()->where('created_at', '<=', $weekAgo)->delete();
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
