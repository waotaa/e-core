<?php

namespace Vng\EvaCore\Jobs;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Exception;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\ElasticResources\ElasticResourceInterface;
use Vng\EvaCore\Http\Middleware\LogJobPayloadSize;
use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Services\ElasticSearch\ElasticApiDocumentResponse;
use Vng\EvaCore\Services\ElasticSearch\ElasticClientBuilder;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchDocumentService;

class SyncBulkResourcesToElasticJob extends ElasticJob
{
    protected string $index;
    protected QueueableCollection $models;
    protected string $resourceClass;
    const BATCH_SIZE = 25;
    protected int $iteration = 1;


    public function __construct(QueueableCollection $models, string $index, string $resourceClass, SyncAttempt $attempt = null)
    {
        parent::__construct($attempt);
        $this->models = $models;
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

        $this->models->chunk(self::BATCH_SIZE)->each(function (Collection $batch) {
            $this->attempt?->addNote('starting iteration ' . $this->iteration);
            $this->indexDocuments($batch);
            $this->attempt?->addNote('finished iteration ' . $this->iteration);
            $this->iteration++;
        });

        $flawless = $this->attempt->countFailedResults() === 0;
        if (!$flawless) {
            $this->attempt?->addNote('success percentage ' . $this->attempt->successPercentage());
            $this->attempt?->addNote('failed items ' . $this->attempt->countFailedResults());
        }
        $status = $flawless ? SyncAttempt::STATUS_SUCCESS : SyncAttempt::STATUS_FAILED;
        $this->attempt?->updateStatus($status);
    }

    protected function indexDocuments(Collection $models)
    {
        Log::info('Syncing resources ['. $this->resourceClass .'] in bulk to index ['. $this->getFullIndex() .']');

        try {
            $docService = ElasticsearchDocumentService::make()
                ->setClient($this->getClient());
            $documentResponses = $docService->bulk($this->generatePayload($models));
            $this->processResponse($documentResponses);

        } catch (NoNodesAvailableException $noNodesAvailableException) {
            $this->attempt?->updateStatus(SyncAttempt::STATUS_NO_NODES);
            $this->release(20);
        } catch (Exception $exception) {
            Log::error('ES >> bulk attempt: Sync failed - model info', [
                'class' => $this->resourceClass,
            ]);
            $this->attempt?->updateStatus(SyncAttempt::STATUS_FAILED);
            throw $exception;
//            throw new Exception('Syncing resource ['. $this->resourceClass .'] in bulk to index ['. $this->getFullIndex() .'] failed', 0, $exception);
        }
    }

    /**
     * @param ElasticApiDocumentResponse[] $documentResponses
     * @return void
     */
    protected function processResponse($documentResponses)
    {
        $resultArray = [];
        foreach ($documentResponses as $response) {
            $resultArray[$response->id] = $response->isSuccess();

            if (!$response->isSuccess()) {
                $this->attempt?->updateNote(json_encode($response->getErrorDetails()));
            }
        }
        $this->attempt?->addResults($resultArray);
    }

    public function getClient(): Client
    {
        return ElasticClientBuilder::make();
    }

    protected function generatePayload(Collection $models)
    {
        $payload = [];
        $models->each(function(SearchableModel $model) use (&$payload) {
            $payload[] = [
                'index' => [
                    '_index' => $this->getFullIndex(),
                    '_id' => $this->getId($model)
                ]
            ];
            $payload[] = $this->getDocument($model);
        });
        return $payload;
    }

    protected function getFullIndex(): string
    {
        return static::prefixIndex($this->index);
    }

    protected function getId(SearchableModel $model): string
    {
        return $model->getSearchId();
    }

    protected function getDocument(SearchableModel $model): array
    {
        $resource = $this->resourceClass::make($model);
        if (!$resource instanceof ElasticResourceInterface) {
            throw new Exception('Invalid resource class provided');
        }
        return $resource->toArray();
    }
}
