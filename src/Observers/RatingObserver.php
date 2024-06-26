<?php

namespace Vng\EvaCore\Observers;

use Illuminate\Support\Facades\Notification;
use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\Rating;
use Vng\EvaCore\Notifications\RatingStoredNotificationInterface;

class RatingObserver
{
    public function created(Rating $rating): void
    {
        $this->syncConnectedElasticResources($rating);
        $this->notifyOfCreation($rating);
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

    private function notifyOfCreation(Rating $rating)
    {
        $notification = app(RatingStoredNotificationInterface::class, [
            'rating' => $rating
        ]);
        $managers = $rating->instrument->watchingUsers;
        Notification::send($managers, $notification);
    }

    private function syncConnectedElasticResources(Rating $rating): void
    {
        if (!is_null($rating->instrument)) {
            ElasticRelatedResourceChanged::dispatch($rating->instrument, $rating);
        }
    }
}
