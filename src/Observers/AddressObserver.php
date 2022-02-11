<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\Address;

class AddressObserver
{
    public function created(Address $address): void
    {
        $this->syncConnectedElasticResources($address);
    }

    public function updated(Address $address): void
    {
        $this->syncConnectedElasticResources($address);
    }

    public function deleted(Address $address): void
    {
        $this->syncConnectedElasticResources($address);
    }

    public function restored(Address $address): void
    {
        $this->syncConnectedElasticResources($address);
    }

    public function forceDeleted(Address $address): void
    {
        $this->syncConnectedElasticResources($address);
    }

    private function syncConnectedElasticResources(Address $address): void
    {
        $addressable = $address->addressable;
        if (!is_null($addressable)) {
            ElasticRelatedResourceChanged::dispatch($addressable, $address);
        }

        $address->providers->each(
            fn($provider) => ElasticRelatedResourceChanged::dispatch($provider, $address)
        );
    }
}
