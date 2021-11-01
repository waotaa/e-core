<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\Provider;

class ProviderObserver
{
    public function created(Provider $provider): void
    {
        $this->syncConnectedElasticResources($provider);
    }

    public function updated(Provider $provider): void
    {
        $this->syncConnectedElasticResources($provider);
    }

    public function deleted(Provider $provider): void
    {
        $this->syncConnectedElasticResources($provider);
    }

    public function restored(Provider $provider): void
    {
        $this->syncConnectedElasticResources($provider);
    }

    public function forceDeleted(Provider $provider): void
    {
        $this->syncConnectedElasticResources($provider);
    }

    private function syncConnectedElasticResources(Provider $provider): void
    {
        $provider->instruments->each(
            fn($instrument) => ElasticRelatedResourceChanged::dispatch($instrument, $provider)
        );
    }
}
