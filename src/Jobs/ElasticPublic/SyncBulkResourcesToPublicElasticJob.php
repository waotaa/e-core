<?php

namespace Vng\EvaCore\Jobs\ElasticPublic;

use Vng\EvaCore\Jobs\SyncBulkResourcesToElasticJob;

class SyncBulkResourcesToPublicElasticJob extends SyncBulkResourcesToElasticJob
{
    use PublicElasticClientTrait;
}
