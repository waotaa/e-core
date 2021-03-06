<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;

class SyncResourceToPublicElasticJob extends SyncResourceToElasticJob
{
    use PublicElasticClientTrait;

    public function __construct(SearchableModel $model, string $index, string $resourceClass, SyncAttempt $attempt = null)
    {
        parent::__construct($model, $index, $resourceClass, $attempt);
    }
}
