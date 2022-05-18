<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Exceptions\ElasticIndexNotFoundException;

class DeleteIndexJob extends ElasticJob
{
    public string $index;

    public function __construct(string $index)
    {
        parent::__construct();
        $this->index = $index;
    }

    public function handle(): void
    {
        $this->indexExists();
        $this->deleteIndex();
    }

    public function indexExists()
    {
        $elasticSearchClient = $this->getClient();
        $prefixedIndex = $this->getFullIndex();
        $exists = $elasticSearchClient->indices()->exists([
            'index' => $prefixedIndex
        ]);
        if (!$exists) {
            throw new ElasticIndexNotFoundException($prefixedIndex . ' not found');
        }
    }

    public function deleteIndex(): bool
    {
        $elasticSearchClient = $this->getClient();
        $prefixedIndex = $this->getFullIndex();
        $response = $elasticSearchClient->indices()->delete([
            'index' => $prefixedIndex
        ]);

        return $response['acknowledged'];
    }

    protected function getFullIndex(): string
    {
        return static::prefixIndex($this->index);
    }
}
