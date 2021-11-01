<?php

namespace Vng\EvaCore\Listeners;

use Vng\EvaCore\Events\ContactAttachedEvent;
use Vng\EvaCore\Events\ContactDetachedEvent;
use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\ElasticResourceRemoved;
use Vng\EvaCore\Events\ElasticResourceSaved;
use Vng\EvaCore\Events\InstrumentAttachedEvent;
use Vng\EvaCore\Events\InstrumentDetachedEvent;
use Vng\EvaCore\Events\ProviderAttachedEvent;
use Vng\EvaCore\Events\ProviderDetachedEvent;
use Vng\EvaCore\Jobs\FetchNewInstrumentRatings;
use Vng\EvaCore\Jobs\RemoveResourceFromElastic;
use Vng\EvaCore\Jobs\SyncResourceToElastic;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\SyncAttempt;
use Carbon\Carbon;
use Exception;
use Illuminate\Events\Dispatcher;

class ElasticResourceEventSubscriber
{
    public function handleRelatedResourceChanged(ElasticRelatedResourceChanged $event)
    {
        if (get_class($event->model) === Instrument::class) {
            FetchNewInstrumentRatings::dispatch($event->model);
        }

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'save';
        $attempt->resource()->associate($event->model);
        $attempt->origin()->associate($event->relatedModel);
        $attempt->save();

        dispatch(new SyncResourceToElastic($event->model, $attempt));
    }

    public function handleInstrumentAttached(InstrumentAttachedEvent $event)
    {
        $instrument_id = $event->pivot->instrument_id;
        /** @var Instrument $instrument */
        $instrument = Instrument::withTrashed()->find($instrument_id);

        if (is_null($instrument)) {
            throw new Exception('No instrument found with id [' . $instrument_id . ']');
        }

        FetchNewInstrumentRatings::dispatch($instrument);

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'attach';
        $attempt->resource()->associate($instrument);
        $attempt->origin()->associate($event->pivot);
        $attempt->save();

        dispatch(new SyncResourceToElastic($instrument, $attempt));
    }
    public function handleInstrumentDetached(InstrumentDetachedEvent $event)
    {
        $instrument_id = $event->pivot->instrument_id;
        /** @var Instrument $instrument */
        $instrument = Instrument::withTrashed()->find($instrument_id);

        if (is_null($instrument)) {
            throw new Exception('No instrument found with id [' . $instrument_id . ']');
        }

        FetchNewInstrumentRatings::dispatch($instrument);

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'detach';
        $attempt->resource()->associate($instrument);
        $attempt->origin()->associate($event->pivot);
        $attempt->save();

        dispatch(new SyncResourceToElastic($instrument, $attempt));
    }

    public function handleProviderAttached(ProviderAttachedEvent $event)
    {
        /** @var Provider $provider */
        $provider = Provider::withTrashed()->find($event->pivot->provider_id);

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'attach';
        $attempt->resource()->associate($provider);
        $attempt->origin()->associate($event->pivot);
        $attempt->save();

        dispatch(new SyncResourceToElastic($provider, $attempt));
    }

    public function handleProviderDetached(ProviderDetachedEvent $event)
    {
        /** @var Provider $provider */
        $provider = Provider::withTrashed()->find($event->pivot->provider_id);

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'detach';
        $attempt->resource()->associate($provider);
        $attempt->origin()->associate($event->pivot);
        $attempt->save();

        dispatch(new SyncResourceToElastic($provider, $attempt));
    }

    public function handleContactAttached(ContactAttachedEvent $event)
    {
        /** @var Contact $contact */
        $contact = Contact::query()->find($event->pivot->contact_id);

        /** @var Instrument|Provider|Region $attached */
        $attached = $event->pivot->contactable_type::query()->find($event->pivot->contactable_id);

        if (get_class($attached) === Instrument::class) {
            FetchNewInstrumentRatings::dispatch($attached);
        }

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'attach';
        $attempt->resource()->associate($contact);
        $attempt->origin()->associate($attached);
        $attempt->save();

        dispatch(new SyncResourceToElastic($attached, $attempt));
    }

    public function handleContactDetached(ContactDetachedEvent $event)
    {
        /** @var Contact $contact */
        $contact = Contact::query()->find($event->pivot->contact_id);

        /** @var Instrument|Provider|Region $attached */
        $detached = $event->pivot->contactable_type::withTrashed()->find($event->pivot->contactable_id);

        if (get_class($detached) === Instrument::class) {
            FetchNewInstrumentRatings::dispatch($detached);
        }

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'detach';
        $attempt->resource()->associate($contact);
        $attempt->origin()->associate($detached);
        $attempt->save();

        dispatch(new SyncResourceToElastic($detached, $attempt));
    }

    public function handleResourceRemoved(ElasticResourceRemoved $event)
    {
        if (get_class($event->model) === Instrument::class) {
            FetchNewInstrumentRatings::dispatch($event->model);
        }

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'remove';
        $attempt->resource()->associate($event->model);
        $attempt->save();

        dispatch(new RemoveResourceFromElastic($event->model, $attempt));
    }

    public function handleResourceSaved(ElasticResourceSaved $event)
    {
        if (get_class($event->model) === Instrument::class) {
            FetchNewInstrumentRatings::dispatch($event->model);
        }

        $this->clearOldAttempts();
        $attempt = new SyncAttempt();
        $attempt->action = 'save';
        $attempt->resource()->associate($event->model);
        $attempt->save();

        dispatch(new SyncResourceToElastic($event->model, $attempt));
    }

    private function clearOldAttempts()
    {
        $weekAgo = Carbon::now()->subDays(7)->startOfDay()->toDateTimeString();
        SyncAttempt::query()->where('created_at', '<=', $weekAgo)->delete();
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            ElasticRelatedResourceChanged::class,
            [ElasticResourceEventSubscriber::class, 'handleRelatedResourceChanged']
        );

        $events->listen(
            ElasticResourceSaved::class,
            [ElasticResourceEventSubscriber::class, 'handleResourceSaved']
        );

        $events->listen(
            ElasticResourceRemoved::class,
            [ElasticResourceEventSubscriber::class, 'handleResourceRemoved']
        );

        $events->listen(
            InstrumentAttachedEvent::class,
            [ElasticResourceEventSubscriber::class, 'handleInstrumentAttached']
        );

        $events->listen(
            InstrumentDetachedEvent::class,
            [ElasticResourceEventSubscriber::class, 'handleInstrumentDetached']
        );

        $events->listen(
            ProviderAttachedEvent::class,
            [ElasticResourceEventSubscriber::class, 'handleProviderAttached']
        );

        $events->listen(
            ProviderDetachedEvent::class,
            [ElasticResourceEventSubscriber::class, 'handleProviderDetached']
        );

        $events->listen(
            ContactAttachedEvent::class,
            [ElasticResourceEventSubscriber::class, 'handleContactAttached']
        );

        $events->listen(
            ContactDetachedEvent::class,
            [ElasticResourceEventSubscriber::class, 'handleContactDetached']
        );
    }
}
