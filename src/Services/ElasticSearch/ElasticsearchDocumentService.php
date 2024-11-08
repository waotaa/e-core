<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Exception;
use Illuminate\Support\Facades\Log;

class ElasticsearchDocumentService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = ElasticClientBuilder::make();
    }

    public static function make(): self
    {
        return new self();
    }

    public function setClient(Client $client): static
    {
        $this->client = $client;
        return $this;
    }

    public function index($indexName, $documentId, $body): ElasticApiDocumentResponse
    {
        if (!ElasticsearchEndpointService::make()->indexExists($indexName)) {
            Log::info('ES >> index attempt: index does not exist');
            throw new \Exception('Index does not exist');
        }

        try {
            $response = $this->client->index([
                'index' => $indexName,
                'id' => $documentId,
                'body' => $body
            ]);
        } catch (NoNodesAvailableException $noNodesAvailableException) {
            Log::error('ES >> index attempt: No nodes available exception', [
                'exception' => $noNodesAvailableException,
                'index' => $indexName,
                'id' => $documentId,
            ]);
            throw $noNodesAvailableException;
        } catch (Exception $exception) {
            Log::error('ES >> index attempt: Sync failed', [
                'exception' => $exception,
                'index' => $indexName,
                'id' => $documentId,
            ]);
            throw $exception;
        }

        Log::info('ES >> index result', ['response' => $response]);

        return ElasticApiDocumentResponse::fromApiResponse($response);
    }

    public function bulk($payload): array
    {
        try {
            $response = $this->client->bulk($payload);
        } catch (NoNodesAvailableException $noNodesAvailableException) {
            Log::error('ES >> bulk attempt: No nodes available exception', [
                'exception' => $noNodesAvailableException,
            ]);
            throw $noNodesAvailableException;
        } catch (Exception $exception) {
            Log::error('ES >> bulk attempt: Sync failed', [
                'exception' => $exception,
            ]);
            throw $exception;
        }

        Log::info('ES >> bulk result', ['response' => $response]);
        return ElasticApiDocumentResponse::fromBulkApiResponse($response);
    }

    public function delete($indexName, $documentId): ElasticApiDocumentResponse
    {
        if (!ElasticsearchEndpointService::make()->indexExists($indexName)) {
            Log::info('ES >> delete attempt: index does not exist');
            throw new \Exception('Index does not exist');
        }

        $response = $this->client->delete([
            'index' => $indexName,
            'id' => $documentId,
        ]);

        Log::info('ES >> delete result', ['response' => $response]);
        return ElasticApiDocumentResponse::fromApiResponse($response);
    }
}
