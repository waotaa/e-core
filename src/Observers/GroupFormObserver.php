<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Models\GroupForm;

class GroupFormObserver
{
    public function created(GroupForm $groupForm): void
    {
        $this->syncConnectedElasticResources($groupForm);
    }

    public function updated(GroupForm $groupForm): void
    {
        $this->syncConnectedElasticResources($groupForm);
    }

    public function deleted(GroupForm $groupForm): void
    {
        $this->syncConnectedElasticResources($groupForm);
    }

    public function restored(GroupForm $groupForm): void
    {
        $this->syncConnectedElasticResources($groupForm);
    }

    public function forceDeleted(GroupForm $groupForm): void
    {
        $this->syncConnectedElasticResources($groupForm);
    }

    private function syncConnectedElasticResources(GroupForm $groupForm): void
    {
        $groupForm->instruments->each(
            function ($instrument) use ($groupForm) {
                ElasticRelatedResourceChanged::dispatch($instrument, $groupForm);
                InstrumentSaved::dispatch($instrument);
            }
        );
    }
}
