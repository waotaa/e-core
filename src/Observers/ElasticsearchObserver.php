<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticResourceRemoved;
use Vng\EvaCore\Events\ElasticResourceSaved;
use Vng\EvaCore\Models\SearchableModel;

class ElasticsearchObserver
{
    public function created(SearchableModel $model): void
    {
        ElasticResourceSaved::dispatch($model);
    }

    public function updated(SearchableModel $model): void
    {
        ElasticResourceSaved::dispatch($model);
    }

    public function deleted(SearchableModel $model): void
    {
        ElasticResourceRemoved::dispatch($model);
    }

    public function restored(SearchableModel $model): void
    {
        ElasticResourceSaved::dispatch($model);
    }
}
