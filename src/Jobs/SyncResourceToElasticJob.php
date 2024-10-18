<?php

namespace Vng\EvaCore\Jobs;

use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\ElasticResources\ElasticResource;
use Vng\EvaCore\Models\SyncAttempt;
use Elasticsearch\Client;
use Vng\EvaCore\Services\ElasticSearch\ElasticClientBuilder;

class SyncResourceToElasticJob extends ElasticJob
{
    protected string $index;
    protected Model $model;
    protected string $resourceClass;

    public function __construct(Model $model, string $index, string $resourceClass, SyncAttempt $attempt = null)
    {
        parent::__construct($attempt);
        $this->model = $model;
        $this->index = $index;
        $this->resourceClass = $resourceClass;
    }

    public function handle(): void
    {
        $this->indexDocument();
    }

    protected function indexDocument()
    {
        $elasticSearchClient = $this->getClient();

        Log::info('Syncing resource ['. get_class($this->getResource()) .'] with id ['. $this->getId() .'] to index ['. $this->getFullIndex() .']');
        $this->updateAttemptStatus('job started');

        try {
            Log::info('Before index attempt');
            $result = $elasticSearchClient->index([
                'index' => $this->getFullIndex(),
    //            'type' => $this->model->getSearchType(),
                'id' => $this->getId(),
                'body' => $this->getResource()->toArray(),
            ]);
            Log::info('ElasticSearch result', ['result' => $result]);

            $this->updateAttemptStatusWithResult($result);
            Log::info('Document indexed successfully', [
                'index' => $this->getFullIndex(),
                'id' => $this->getId(),
                'result' => $result,
            ]);
        } catch (NoNodesAvailableException $noNodesAvailableException) {
            Log::warning('No nodes available exception', [
                'exception' => $noNodesAvailableException,
                'index' => $this->getFullIndex(),
                'id' => $this->getId(),
            ]);
            $this->updateAttemptStatus('no nodes');
            $this->release(20);
        } catch (Exception $exception) {
            Log::error('Sync failed', [
                'exception' => $exception,
                'model_id' => $this->model->id,
                'model' => $this->model,
                'class' => $this->resourceClass,
                'index' => $this->getFullIndex(),
                'id' => $this->getId(),
            ]);
            $this->updateAttemptStatus('failed');
            throw new Exception('Syncing resource ['. $this->resourceClass .'] with model id ['. $this->model->id .'] to index ['. $this->getFullIndex() .'] failed', 0, $exception);
//            throw $exception;
        }
    }

    public function getClient(): Client
    {
        return ElasticClientBuilder::make();
    }

    protected function getIndex(): string
    {
        return $this->index;
    }

    protected function getFullIndex(): string
    {
        return static::prefixIndex($this->index);
    }

    protected function getId(): string
    {
        return $this->model->getSearchId();
    }

    protected function getResource(): ElasticResource
    {
        $resource = $this->resourceClass::make($this->model);
        if (!$resource instanceof ElasticResource) {
            throw new Exception('Invalid resource class provided');
        }
        return $resource;
    }
}
