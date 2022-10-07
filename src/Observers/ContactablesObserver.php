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

    public function deleting(Contactables $contactables): void
    {
        $contactables = $contactables->fresh();
        ContactDetachedEvent::dispatch($contactables->contact, $contactables->contactable);
    }

    public function restored(Contactables $contactables): void
    {
        $this->attachConnectedElasticResources($contactables);
    }

    private function attachConnectedElasticResources(Contactables $contactables): void
    {
        ContactAttachedEvent::dispatch($contactables->contact, $contactables->contactable);
    }
}
