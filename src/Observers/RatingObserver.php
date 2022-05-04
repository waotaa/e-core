<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\Rating;

class RatingObserver
{
    public function created(Rating $rating): void
    {
        $this->syncConnectedElasticResources($rating);
    }

    public function updated(Rating $rating): void
    {
        $this->syncConnectedElasticResources($rating);
    }

    public function deleted(Rating $rating): void
    {
        $this->syncConnectedElasticResources($rating);
    }

    public function restored(Rating $rating): void
    {
        $this->syncConnectedElasticResources($rating);
    }

    private function syncConnectedElasticResources(Rating $rating): void
    {
        if (!is_null($rating->instrument)) {
            ElasticRelatedResourceChanged::dispatch($rating->instrument, $rating);
        }
    }
}
