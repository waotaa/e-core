<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\InstrumentSaved;
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

    private function syncConnectedElasticResources(Implementation $implementation): void
    {
        $implementation->instruments->each(
            function($instrument) use ($implementation) {
                ElasticRelatedResourceChanged::dispatch($instrument, $implementation);
                InstrumentSaved::dispatch($instrument);
            }
        );
    }
}
