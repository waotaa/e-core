<?php

namespace Vng\EvaCore\Services\ElasticSearch;

use Elasticsearch\Client;

class ElasticsearchEndpointService
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

    public function getClusterSettings(): array
    {
        try {
            return $this->client->cluster()->getSettings([
                'flat_settings' => true
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to fetch cluster settings: ' . $e->getMessage());
        }
    }

    public function createIndex(string $indexName, array $settings = [], array $mappings = []): bool
    {
        try {
            $params = [
                'index' => $indexName,
                'body'  => []
            ];

            if (!empty($settings)) {
                $params['body']['settings'] = $settings;
            }

            if (!empty($mappings)) {
                $params['body']['mappings'] = $mappings;
            }

            $this->client->indices()->create($params);
            return true;
        } catch (\Exception $e) {
            // Log de fout of handel af zoals nodig
            return false;
        }
    }

    public function getIndexSettings(string $index): array
    {
        try {
            // Haal de instellingen van de index op
            $response = $this->client->indices()->getSettings([
                'index' => $index
            ]);

            // Retourneer de settings van de opgegeven index
            return $response[$index]['settings'] ?? [];
        } catch (\Exception $e) {
            // Log de fout of handel af zoals nodig
            return [];
        }
    }

    public function updateIndexSettings(string $index, array $settings): array
    {
        return $this->client->indices()->putSettings([
            'index' => $index,
            'body' => [
                'settings' => $settings
            ]
        ]);
    }

    public function getIndexes(): array
    {
        $response = $this->client->cat()->indices([
            'format' => 'json',
        ]);

        // Haal de indexnamen op uit het antwoord
        return array_map(function ($index) {
            return $index['index'];
        }, $response);
    }

    public function getTemplates(?string $indexName = null): array
    {
        try {
            // Haal alle templates op uit Elasticsearch
            $templates = $this->client->indices()->getTemplate();

            // Als geen indexnaam is opgegeven, retourneer alle templates
            if (is_null($indexName)) {
                return $templates;
            }

            // Zoek naar templates die van toepassing zijn op de gegeven index
            $applicableTemplates = [];
            foreach ($templates as $templateName => $templateDetails) {
                if (isset($templateDetails['index_patterns'])) {
                    foreach ($templateDetails['index_patterns'] as $pattern) {
                        // Controleer of het indexpatroon overeenkomt met de gegeven index
                        if (fnmatch($pattern, $indexName)) {
                            $applicableTemplates[$templateName] = $templateDetails;
                        }
                    }
                }
            }

            return $applicableTemplates;
        } catch (\Exception $e) {
            // Log de fout of handel het af zoals nodig
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Check if the given index exists in Elasticsearch.
     *
     * @param string $index
     * @return bool
     */
    public function indexExists(string $index): bool
    {
        $params = ['index' => $index];
        return $this->client->indices()->exists($params);
//        try {
//        } catch (\Exception $e) {
//            return false;
//        }
    }

    /**
     * Retrieve a document by index and id from Elasticsearch.
     *
     * @param string $index
     * @param string $id
     * @return array|null
     */
    public function getDocument(string $index, string $id): ?array
    {
        $params = [
            'index' => $index,
            'id' => $id
        ];

        try {
            $response = $this->client->get($params);
            return $response['_source'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Retrieve the mapping for a given index from Elasticsearch.
     *
     * @param string $index
     * @return array|null
     */
    public function getMapping(string $index): ?array
    {
        $params = ['index' => $index];

        try {
            return $this->client->indices()->getMapping($params);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Retrieve all documents from a given index.
     *
     * @param string $index
     * @return array
     */
    public function getAllDocuments(string $index): array
    {
        $params = [
            'index' => $index,
            'body' => [
                'query' => [
                    'match_all' => new \stdClass()
                ],
                'size' => 1000
            ]
        ];

        try {
            $response = $this->client->search($params);
            // Map over the hits to return only the _source field
            return array_map(function ($hit) {
                return [
                    'id' => $hit['_id'], // The document ID
                    'source' => $hit['_source'] ?? [] // The document source
                ];
            }, $response['hits']['hits'] ?? []);
        } catch (\Exception $e) {
            return [];
        }
    }
}
