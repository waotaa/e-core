<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Models\Location;

class LocationObserver
{
    public function created(Location $location): void
    {
        $this->syncConnectedElasticResources($location);
    }

    public function updated(Location $location): void
    {
        $this->syncConnectedElasticResources($location);
    }

    public function deleted(Location $location): void
    {
        $this->syncConnectedElasticResources($location);
    }

    public function restored(Location $location): void
    {
        $this->syncConnectedElasticResources($location);
    }

    private function syncConnectedElasticResources(Location $location): void
    {
        if (!is_null($location->instrument)) {
            ElasticRelatedResourceChanged::dispatch($location->instrument, $location);
            InstrumentSaved::dispatch($location->instrument);
        }
    }
}
