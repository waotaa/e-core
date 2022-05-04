<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Models\NewsItem;

class NewsItemObserver
{
    public function created(NewsItem $newsItem): void
    {
        $this->syncConnectedElasticResources($newsItem);
    }

    public function updated(NewsItem $newsItem): void
    {
        $this->syncConnectedElasticResources($newsItem);
    }

    public function deleted(NewsItem $newsItem): void
    {
        $this->syncConnectedElasticResources($newsItem);
    }

    public function restored(NewsItem $newsItem): void
    {
        $this->syncConnectedElasticResources($newsItem);
    }

    private function syncConnectedElasticResources(NewsItem $newsItem): void
    {
        if (!is_null($newsItem->environment)) {
            ElasticRelatedResourceChanged::dispatch($newsItem->environment, $newsItem);
        }
    }
}
