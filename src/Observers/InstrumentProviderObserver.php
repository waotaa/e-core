<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\InstrumentAttachedEvent;
use Vng\EvaCore\Events\InstrumentDetachedEvent;
use Vng\EvaCore\Events\ProviderAttachedEvent;
use Vng\EvaCore\Events\ProviderDetachedEvent;
use Vng\EvaCore\Models\InstrumentProvider;

class InstrumentProviderObserver
{
    public function created(InstrumentProvider $instrumentProvider): void
    {
        $this->attachConnectedElasticResources($instrumentProvider);
    }

    public function updated(InstrumentProvider $instrumentProvider): void
    {
        $this->attachConnectedElasticResources($instrumentProvider);
    }

    public function deleted(InstrumentProvider $instrumentProvider): void
    {
        $this->detachConnectedElasticResources($instrumentProvider);
    }

    public function restored(InstrumentProvider $instrumentProvider): void
    {
        $this->attachConnectedElasticResources($instrumentProvider);
    }

    private function attachConnectedElasticResources(InstrumentProvider $instrumentProvider): void
    {
        InstrumentAttachedEvent::dispatch($instrumentProvider);
        ProviderAttachedEvent::dispatch($instrumentProvider);
    }

    private function detachConnectedElasticResources(InstrumentProvider $instrumentProvider): void
    {
        InstrumentDetachedEvent::dispatch($instrumentProvider);
        ProviderDetachedEvent::dispatch($instrumentProvider);
    }
}
