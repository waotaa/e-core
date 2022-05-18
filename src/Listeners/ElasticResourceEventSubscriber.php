<?php

namespace Vng\EvaCore\Listeners;

use Illuminate\Support\Facades\Bus;
use Vng\EvaCore\Events\ContactAttachedEvent;
use Vng\EvaCore\Events\ContactDetachedEvent;
use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\ElasticResourceRemoved;
use Vng\EvaCore\Events\ElasticResourceSaved;
use Vng\EvaCore\Events\InstrumentAttachedEvent;
use Vng\EvaCore\Events\InstrumentDetachedEvent;
use Vng\EvaCore\Events\ProviderAttachedEvent;
use Vng\EvaCore\Events\ProviderDetachedEvent;
use Vng\EvaCore\Jobs\FetchNewInstrumentRatingsJob;
use Vng\EvaCore\Jobs\PruneSyncAttempts;
use Vng\EvaCore\Jobs\RemoveResourceFromElasticJob;
use Vng\EvaCore\Jobs\SyncSearchableModelToElasticJob;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Region;
use Exception;
use Illuminate\Events\Dispatcher;
use Vng\EvaCore\Services\ElasticSearch\SyncService;

class ElasticResourceEventSubscriber
{
    public function handleRelatedResourceChanged(ElasticRelatedResourceChanged $event)
    {
        $relatedModel = $event->model;
        $attempt = SyncService::createSyncAttempt($relatedModel, 'save', 'created');
        $attempt = SyncService::addRelatedModel($attempt, $event->relatedModel);

        $jobs = [
            new SyncSearchableModelToElasticJob($relatedModel, $attempt),
            new PruneSyncAttempts()
        ];

        if (get_class($relatedModel) === Instrument::class) {
            array_unshift($jobs, new FetchNewInstrumentRatingsJob($relatedModel));
        }

        Bus::chain($jobs)->dispatch();
    }

    public function handleInstrumentAttached(InstrumentAttachedEvent $event)
    {
        $instrument_id = $event->pivot->instrument_id;
        /** @var Instrument $instrument */
        $instrument = Instrument::withTrashed()->find($instrument_id);

        if (is_null($instrument)) {
            throw new Exception('No instrument found with id [' . $instrument_id . ']');
        }

        $attempt = SyncService::createSyncAttempt($instrument, 'attach', 'created');
        $attempt = SyncService::addRelatedModel($attempt, $event->pivot);

        Bus::chain([
            new FetchNewInstrumentRatingsJob($instrument),
            new SyncSearchableModelToElasticJob($instrument, $attempt),
            new PruneSyncAttempts()
        ])->dispatch();
    }

    public function handleInstrumentDetached(InstrumentDetachedEvent $event)
    {
        $instrument_id = $event->pivot->instrument_id;
        /** @var Instrument $instrument */
        $instrument = Instrument::withTrashed()->find($instrument_id);

        if (is_null($instrument)) {
            throw new Exception('No instrument found with id [' . $instrument_id . ']');
        }

        $attempt = SyncService::createSyncAttempt($instrument, 'detach', 'created');
        $attempt = SyncService::addRelatedModel($attempt, $event->pivot);

        Bus::chain([
            new FetchNewInstrumentRatingsJob($instrument),
            new SyncSearchableModelToElasticJob($instrument, $attempt),
            new PruneSyncAttempts()
        ])->dispatch();
    }

    public function handleProviderAttached(ProviderAttachedEvent $event)
    {
        /** @var Provider $provider */
        $provider = Provider::withTrashed()->find($event->pivot->provider_id);

        $attempt = SyncService::createSyncAttempt($provider, 'attach', 'created');
        $attempt = SyncService::addRelatedModel($attempt, $event->pivot);

        Bus::chain([
            new SyncSearchableModelToElasticJob($provider, $attempt),
            new PruneSyncAttempts()
        ])->dispatch();
    }

    public function handleProviderDetached(ProviderDetachedEvent $event)
    {
        /** @var Provider $provider */
        $provider = Provider::withTrashed()->find($event->pivot->provider_id);

        $attempt = SyncService::createSyncAttempt($provider, 'detach', 'created');
        $attempt = SyncService::addRelatedModel($attempt, $event->pivot);

        Bus::chain([
            new SyncSearchableModelToElasticJob($provider, $attempt),
            new PruneSyncAttempts()
        ])->dispatch();
    }

    public function handleContactAttached(ContactAttachedEvent $event)
    {
        $contact = $event->contact;

        /** @var Instrument|Provider|Region $attached */
        $attached = $event->contactable;

        $attempt = SyncService::createSyncAttempt($attached, 'attach', 'created');
        $attempt = SyncService::addRelatedModel($attempt, $contact);

        $jobs = [
            new SyncSearchableModelToElasticJob($attached, $attempt),
            new PruneSyncAttempts()
        ];

        if (get_class($attached) === Instrument::class) {
            array_unshift($jobs, new FetchNewInstrumentRatingsJob($attached));
        }

        Bus::chain($jobs)->dispatch();
    }

    public function handleContactDetached(ContactDetachedEvent $event)
    {
        $contact = $event->contact;

        /** @var Instrument|Provider|Region $detached */
        $detached = $event->contactable;

        $attempt = SyncService::createSyncAttempt($detached, 'detach', 'created');
        $attempt = SyncService::addRelatedModel($attempt, $contact);

        $jobs = [
            new SyncSearchableModelToElasticJob($detached, $attempt),
            new PruneSyncAttempts()
        ];

        if (get_class($detached) === Instrument::class) {
            array_unshift($jobs, new FetchNewInstrumentRatingsJob($detached));
        }

        Bus::chain($jobs)->dispatch();
    }

    public function handleResourceRemoved(ElasticResourceRemoved $event)
    {
        $searchableModel = $event->model;
        $attempt = SyncService::createSyncAttempt($searchableModel, 'remove', 'created');

        $jobs = [
            new RemoveResourceFromElasticJob($searchableModel->getSearchIndex(), $searchableModel->getSearchId(), $attempt),
            new PruneSyncAttempts()
        ];

        if (get_class($searchableModel) === Instrument::class) {
            array_unshift($jobs, new FetchNewInstrumentRatingsJob($searchableModel));
        }

        Bus::chain($jobs)->dispatch();
    }

    public function handleResourceSaved(ElasticResourceSaved $event)
    {
        $searchableModel = $event->model;
        $attempt = SyncService::createSyncAttempt($searchableModel, 'save', 'created');

        $jobs = [
            new SyncSearchableModelToElasticJob($searchableModel, $attempt),
            new PruneSyncAttempts()
        ];

        if (get_class($searchableModel) === Instrument::class) {
            array_unshift($jobs, new FetchNewInstrumentRatingsJob($searchableModel));
        }

        Bus::chain($jobs)->dispatch();
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
