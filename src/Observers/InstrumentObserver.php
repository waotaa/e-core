<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\InstrumentRemoved;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentType;
use Vng\EvaCore\Services\ModelHelpers\InstrumentTrackerHelper;

class InstrumentObserver
{
    public function created(Instrument $instrument): void
    {
        InstrumentSaved::dispatch($instrument);
        $this->syncConnectedElasticResources($instrument);

        $user = request()->user();
        if ($user) {
            InstrumentTrackerHelper::createQuickTrackerForCreator($instrument, $user->manager);
        }
    }

    public function updated(Instrument $instrument): void
    {
        InstrumentSaved::dispatch($instrument);
        $this->syncConnectedElasticResources($instrument);

        $user = request()->user();
        if ($user) {
            InstrumentTrackerHelper::createQuickTrackerForAuthor($instrument, $user->manager);
        }
    }

    public function saving(Instrument $instrument): void
    {
        $dedicatedType = config('eva-core.instrument.dedicatedType');
        if ($dedicatedType) {
            $instrumentType = InstrumentType::query()->where('name', $dedicatedType)->first();
            if (is_null($instrumentType)) {
                throw new \Exception('Cannot find instrument type with current dedicated instrument type config');
            }
            $instrument->instrumentType()->associate($instrumentType);
        }
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
