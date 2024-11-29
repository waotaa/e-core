<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Exception;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Services\ElasticSearch\Clients\ElasticClientBuilder;

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
            Log::info("ES >> index attempt: index {$indexName} does not exist");
//            throw new \Exception("Index {$indexName} does not exist");
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
            $response = $this->client->bulk([
                'body' => $payload
            ]);
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

    public function delete($indexName, $documentId): ?ElasticApiDocumentResponse
    {
        if (!ElasticsearchEndpointService::make()->indexExists($indexName)) {
            Log::info('ES >> delete attempt: index does not exist');
//            throw new \Exception('Index does not exist');
            return null;
        }
        if (!ElasticsearchEndpointService::make()->getDocument($indexName, $documentId)) {
            Log::info('ES >> delete attempt: document does not exist');
            return null;
        }

        $response = $this->client->delete([
            'index' => $indexName,
            'id' => $documentId,
        ]);

        Log::info('ES >> delete result', ['response' => $response]);
        return ElasticApiDocumentResponse::fromApiResponse($response);
    }

    public function getAllDocuments($indexName): array
    {
        return $this->scrollSearch($indexName);
    }

    public function scrollSearch(string $indexName, array $query = ['match_all' => []]): array
    {
        if (!ElasticsearchEndpointService::make()->indexExists($indexName)) {
            Log::info("ES >> scroll search attempt: index {$indexName} does not exist");
            return [];
        }

        try {
            // Eerste zoekopdracht met scroll-parameter
            $response = $this->client->search([
                'index' => $indexName,
                'scroll' => '1m', // Scroll-venster van 1 minuut
                'body' => [
                    'query' => $query,
                ],
            ]);

            $scrollId = $response['_scroll_id'];
            $documents = $response['hits']['hits'];

            // Scroll herhalen tot alle documenten zijn opgehaald
            while (count($response['hits']['hits']) > 0) {
                $response = $this->client->scroll([
                    'scroll_id' => $scrollId,
                    'scroll' => '1m',
                ]);

                $scrollId = $response['_scroll_id'];
                $documents = array_merge($documents, $response['hits']['hits']);
            }

            // Scroll ID opschonen
            $this->client->clearScroll([
                'scroll_id' => $scrollId,
            ]);
        } catch (Exception $exception) {
            Log::error('ES >> scroll search attempt failed', [
                'exception' => $exception,
                'index' => $indexName,
            ]);
            throw $exception;
        }

        Log::info("ES >> scroll search result", ['total' => count($documents)]);
        return $documents;
    }

}
