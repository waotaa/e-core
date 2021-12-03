<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Models\SyncAttempt;
use Elasticsearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Vng\EvaCore\Services\ElasticSearch\SyncService;

class RemoveResourceFromElastic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $backoff = 3;

    private string $index;
    private $id;
    private ?SyncAttempt $attempt;

    public function __construct(string $index, $id, SyncAttempt $attempt = null)
    {
        $this->id = $id;
        $this->index = $index;
        $this->attempt = $attempt;
    }

    public function handle(): void
    {
        /** @var Client $elasticsearch */
        $elasticsearch = App::make('elasticsearch');

        $index = $this->index;
        $prefix = config('elastic.prefix');
        $full_index = $prefix ? $prefix . '-' . $index : $index;

        $exists = $elasticsearch->exists([
            'index' => $full_index,
            'id' => $this->id,
        ]);
        if (!$exists) {
            if (!is_null($this->attempt)) {
                SyncService::updateStatus($this->attempt, 'Resource not found');
            }
            return;
        }

        $result = $elasticsearch->delete([
            'index' => $full_index,
            'id' => $this->id,
        ]);

        if (!is_null($this->attempt)){
            $status = $result['_shards']['failed'] === 0 ? 'succes' : 'failed';
            SyncService::updateStatus($this->attempt, $status);
        }
    }
}
