<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\InstrumentRemoved;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Models\Instrument;

class InstrumentObserver
{
    public function created(Instrument $instrument): void
    {
        InstrumentSaved::dispatch($instrument);
        $this->syncConnectedElasticResources($instrument);
    }

    public function updated(Instrument $instrument): void
    {
        InstrumentSaved::dispatch($instrument);
        $this->syncConnectedElasticResources($instrument);
    }

    public function deleted(Instrument $instrument): void
    {
        InstrumentRemoved::dispatch($instrument);
        $this->syncConnectedElasticResources($instrument);
    }

    public function restored(Instrument $instrument): void
    {
        InstrumentSaved::dispatch($instrument);
        $this->syncConnectedElasticResources($instrument);
    }

    private function syncConnectedElasticResources(Instrument $instrument): void
    {
        if ($instrument->provider) {
            ElasticRelatedResourceChanged::dispatch($instrument->provider, $instrument);
        }

//        deprecated
//        No more muliple providers on instrument
//        $instrument->providers->each(
//            fn($provider) => ElasticRelatedResourceChanged::dispatch($provider, $instrument)
//        );
    }
}
