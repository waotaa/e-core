<?php

namespace Vng\EvaCore\Jobs\ElasticPublic;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Exception;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\ElasticResources\ElasticResourceInterface;
use Vng\EvaCore\Http\Middleware\LogJobPayloadSize;
use Vng\EvaCore\Jobs\SyncBulkResourcesToElasticJob;
use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Services\ElasticSearch\ElasticApiDocumentResponse;
use Vng\EvaCore\Services\ElasticSearch\ElasticClientBuilder;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchDocumentService;

class SyncBulkResourcesToPublicElasticJob extends SyncBulkResourcesToElasticJob
{
    use PublicElasticClientTrait;
}
