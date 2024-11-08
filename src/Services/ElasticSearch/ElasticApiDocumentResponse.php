<?php

namespace Vng\EvaCore\Services\ElasticSearch;

class ElasticApiDocumentResponse
{
    public $id;
    public ?string $result;
    public ?int $failedShards;

    public function __construct(array $data)
    {
        $this->id = $data['_id'] ?? null;
        $this->result = $data['result'] ?? null;
        $this->failedShards = $data['_shards']['failed'] ?? null;
    }

    public static function fromApiResponse(array $data): self
    {
        return new self($data);
    }

    public static function fromBulkApiResponse(array $bulkResponse): array
    {
        $responses = [];
        foreach ($bulkResponse['items'] as $item) {
            $action = array_key_first($item); // "index", "create", "update", etc.
            $data = $item[$action];
            $responses[] = new self($data);
        }
        return $responses;
    }

    public function isSuccess(): bool
    {
        $positiveResults = [
            'created',
            'updated',
            'deleted'
        ];
        $negativeResults = [
            'noop',
            'not_found'
        ];
        $positiveResult = null;
        if (in_array($this->result, $positiveResults)) {
            $positiveResult = true;
        }
        if (in_array($this->result, $negativeResults)) {
            $positiveResult = false;
        }

        return $this->failedShards === 0 && $positiveResult;
    }
}