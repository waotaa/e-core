<?php

namespace Vng\EvaCore\Jobs;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\ElasticResources\ElasticResourceInterface;
use Vng\EvaCore\Http\Middleware\LogJobPayloadSize;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Services\ElasticSearch\ElasticClientBuilder;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchDocumentService;

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

    public function middleware()
    {
        return [new LogJobPayloadSize()];
    }

    public function handle(): void
    {
        $this->attempt?->updateStatus(SyncAttempt::STATUS_STARTED);
        $this->indexDocument();
    }

    protected function indexDocument()
    {
        Log::info('Syncing resource ['. get_class($this->getResource()) .'] with id ['. $this->getId() .'] to index ['. $this->getFullIndex() .']');

        try {
            $docService = ElasticsearchDocumentService::make()
                ->setClient($this->getClient());
            $documentResponse = $docService->index(
                $this->getFullIndex(),
                $this->getId(),
                $this->getResource()->toArray()
            );

            $status = $documentResponse->isSuccess() ? SyncAttempt::STATUS_SUCCESS : SyncAttempt::STATUS_FAILED;
            Log::info("ES >> index attempt: request completed with status {$status}");
            $this->attempt?->updateStatus($status);
        } catch (NoNodesAvailableException) {
            $this->attempt?->updateStatus(SyncAttempt::STATUS_NO_NODES);
            $this->release(20);
        } catch (Exception $exception) {
            Log::error("ES >> index attempt: Sync failed - model info", [
                'model_id' => $this->model->id,
                'model' => $this->model,
                'class' => $this->resourceClass,
            ]);
            $this->attempt?->updateStatus(SyncAttempt::STATUS_FAILED);
            throw new Exception('Syncing resource ['. $this->resourceClass .'] with model id ['. $this->model->id .'] to index ['. $this->getFullIndex() .'] failed', 0, $exception);
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

    protected function getResource(): ElasticResourceInterface
    {
        $resource = $this->resourceClass::make($this->model);
        if (!$resource instanceof ElasticResourceInterface) {
            throw new Exception('Invalid resource class provided');
        }
        return $resource;
    }
}
