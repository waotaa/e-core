<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Models\TargetGroup;

class TargetGroupObserver
{
    public function created(TargetGroup $targetGroup): void
    {
        $this->syncConnectedElasticResources($targetGroup);
    }

    public function updated(TargetGroup $targetGroup): void
    {
        $this->syncConnectedElasticResources($targetGroup);
    }

    public function deleted(TargetGroup $targetGroup): void
    {
        $this->syncConnectedElasticResources($targetGroup);
    }

    public function restored(TargetGroup $targetGroup): void
    {
        $this->syncConnectedElasticResources($targetGroup);
    }

    private function syncConnectedElasticResources(TargetGroup $targetGroup): void
    {
        $targetGroup->instruments->each(
            function ($instrument) use ($targetGroup) {
                ElasticRelatedResourceChanged::dispatch($instrument, $targetGroup);
                InstrumentSaved::dispatch($instrument);
            }
        );
    }
}
