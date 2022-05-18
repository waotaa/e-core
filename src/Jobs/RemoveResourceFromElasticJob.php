<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Models\SyncAttempt;

class RemoveResourceFromElasticJob extends ElasticJob
{
    protected string $index;
    protected $id;

    public function __construct(string $index, $id, SyncAttempt $attempt = null)
    {
        $this->id = $id;
        $this->index = $index;
        parent::__construct($attempt);
    }

    public function handle(): void
    {
        $elasticSearchClient = $this->getClient();

        $prefixedIndex = $this->getFullIndex();

        $this->updateAttemptStatus('Executing');

        $exists = $elasticSearchClient->exists([
            'index' => $prefixedIndex,
            'id' => $this->id,
        ]);
        if (!$exists) {
            $this->updateAttemptStatus('Resource not found');
            return;
        }

        // Delete
        $result = $elasticSearchClient->delete([
            'index' => $prefixedIndex,
            'id' => $this->id,
        ]);
        $this->updateAttemptStatusWithResult($result);
    }

    protected function getFullIndex(): string
    {
        return static::prefixIndex($this->index);
    }
}
