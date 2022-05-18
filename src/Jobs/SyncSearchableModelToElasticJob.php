<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;

class SyncSearchableModelToElasticJob extends SyncResourceToElasticJob
{
    public function __construct(SearchableModel $model, SyncAttempt $attempt = null)
    {
        parent::__construct($model, $model->getSearchIndex(), $model->getResourceClass(), $attempt);
    }

    protected function getId(): string
    {
        return $this->model->getSearchId();
    }
}
