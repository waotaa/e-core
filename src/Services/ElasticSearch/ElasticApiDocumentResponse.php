<?php

namespace Vng\EvaCore\Services\ElasticSearch;

class ElasticApiDocumentResponse
{
//    public $originalData;

    public $id;
    public ?string $result;
    public ?int $failedShards;

    public $status;
    public $errorType;
    public $errorReason;

    public function __construct(array $data)
    {
//        $this->originalData = $data;
        $this->id = $data['_id'] ?? null;

        $this->result = $data['result'] ?? null;
        $this->failedShards = $data['_shards']['failed'] ?? null;

        $this->status = $data['status'] ?? null; // Voeg de HTTP-statuscode toe
        // Opslaan van fout details als het document gefaald is
        if (isset($data['error'])) {
            $this->errorType = $data['error']['type'] ?? 'unknown';
            $this->errorReason = $data['error']['reason'] ?? 'unknown';
        }
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
        if (!in_array($this->status, [200, 201], true)) {
            return false;
        }

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

        return $positiveResult;
//        return $this->failedShards === 0 && $positiveResult;
    }

    public function getErrorDetails(): ?array
    {
        if (!$this->isSuccess() && isset($this->errorType, $this->errorReason)) {
            return [
                'type' => $this->errorType,
                'reason' => $this->errorReason,
            ];
        }

        return null;
    }
}