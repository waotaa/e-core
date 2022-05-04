<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\Contact;

class ContactObserver
{
    public function created(Contact $contact): void
    {
        $this->syncConnectedElasticResources($contact);
    }

    public function updated(Contact $contact): void
    {
        $this->syncConnectedElasticResources($contact);
    }

    public function deleted(Contact $contact): void
    {
        $this->syncConnectedElasticResources($contact);
    }

    public function restored(Contact $contact): void
    {
        $this->syncConnectedElasticResources($contact);
    }

    private function syncConnectedElasticResources(Contact $contact): void
    {
        $contact->instruments->each(
            fn($instrument) => ElasticRelatedResourceChanged::dispatch($instrument, $contact)
        );
        $contact->providers->each(
            fn($provider) => ElasticRelatedResourceChanged::dispatch($provider, $contact)
        );
        $contact->environments->each(
            fn($environment) => ElasticRelatedResourceChanged::dispatch($environment, $contact)
        );
        $contact->regions->each(
            fn($region) => ElasticRelatedResourceChanged::dispatch($region, $contact)
        );
    }
}
