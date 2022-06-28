<?php

namespace Vng\EvaCore\Jobs;

use Elasticsearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vng\EvaCore\Models\SyncAttempt;
use Vng\EvaCore\Services\ElasticSearch\ElasticClientBuilder;
use Vng\EvaCore\Services\ElasticSearch\SyncService;

abstract class ElasticJob implements ElasticJobInterface, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
//    public $backoff = 3;

    protected ?SyncAttempt $attempt;

    public function __construct(SyncAttempt $attempt = null)
    {
        $this->attempt = $attempt;
    }

    public function backoff()
    {
        return [1, 3];
    }

    public function getClient(): Client
    {
        return ElasticClientBuilder::make();
    }

    public static function prefixIndex($index): string
    {
        $prefix = config('elastic.prefix');
        return $prefix ? $prefix . '-' . $index : $index;
    }

    protected function updateAttemptStatus($status): void
    {
        if (!is_null($this->attempt)){
            SyncService::updateStatus($this->attempt, $status);
        }
    }

    protected function updateAttemptStatusWithResult($result): void
    {
        $status = $result['_shards']['failed'] === 0 ? 'succes' : 'failed';
        $this->updateAttemptStatus($status);
    }
}
