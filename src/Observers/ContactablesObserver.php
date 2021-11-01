<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ContactAttachedEvent;
use Vng\EvaCore\Events\ContactDetachedEvent;
use Vng\EvaCore\Models\Contactables;

class ContactablesObserver
{
    public function created(Contactables $contactables): void
    {
        $this->attachConnectedElasticResources($contactables);
    }

    public function updated(Contactables $contactables): void
    {
        $this->attachConnectedElasticResources($contactables);
    }

    public function deleted(Contactables $contactables): void
    {
        $this->detachConnectedElasticResources($contactables);
    }

    public function restored(Contactables $contactables): void
    {
        $this->attachConnectedElasticResources($contactables);
    }

    public function forceDeleted(Contactables $contactables): void
    {
        $this->detachConnectedElasticResources($contactables);
    }

    private function attachConnectedElasticResources(Contactables $contactables): void
    {
        ContactAttachedEvent::dispatch($contactables);
    }

    private function detachConnectedElasticResources(Contactables $contactables): void
    {
        ContactDetachedEvent::dispatch($contactables);
    }
}
