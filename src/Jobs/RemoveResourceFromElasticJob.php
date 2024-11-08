<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Services\ElasticSearch\ElasticsearchDocumentService;

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
        $this->attempt?->updateStatus(SyncAttempt::STATUS_STARTED);

        $prefixedIndex = $this->getFullIndex();
        $docService = ElasticsearchDocumentService::make()
            ->setClient($this->getClient());
        $documentResponse = $docService->delete($prefixedIndex, $this->id);

        $status = $documentResponse->isSuccess() ? SyncAttempt::STATUS_SUCCESS : SyncAttempt::STATUS_FAILED;
        $this->attempt?->updateStatus($status);
    }

    protected function getFullIndex(): string
    {
        return static::prefixIndex($this->index);
    }
}
