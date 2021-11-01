<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\Implementation;

class ImplementationObserver
{
    public function created(Implementation $implementation): void
    {
        $this->syncConnectedElasticResources($implementation);
    }

    public function updated(Implementation $implementation): void
    {
        $this->syncConnectedElasticResources($implementation);
    }

    public function deleted(Implementation $implementation): void
    {
        $this->syncConnectedElasticResources($implementation);
    }

    public function restored(Implementation $implementation): void
    {
        $this->syncConnectedElasticResources($implementation);
    }

    public function forceDeleted(Implementation $implementation): void
    {
        $this->syncConnectedElasticResources($implementation);
    }

    private function syncConnectedElasticResources(Implementation $implementation): void
    {
        $implementation->instruments->each(
            fn($instrument) => ElasticRelatedResourceChanged::dispatch($instrument, $implementation)
        );
    }
}
