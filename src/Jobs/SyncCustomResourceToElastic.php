<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\ElasticResources\ElasticResource;
use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;
use Elasticsearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class SyncCustomResourceToElastic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private SearchableModel $model;
    private string $index;
    private ElasticResource $resource;
    private ?SyncAttempt $attempt;

    public function __construct(SearchableModel $model, string $index, ElasticResource $resource, SyncAttempt $attempt = null)
    {
        $this->model = $model;
        $this->index = $index;
        $this->resource = $resource;
        $this->attempt = $attempt;
    }

    public function handle(): void
    {
        /** @var Client $elastic */
        $elasticsearch = App::make('elasticsearch');

        $index = $this->index;
        $prefix = config('elastic.prefix');

        $result = $elasticsearch->index([
            'index' => $prefix ? $prefix.'-'.$index : $index,
            'type' => $this->model->getSearchType(),
            'id' => $this->model->getSearchId(),
            'body' => $this->resource->toArray(),
        ]);

        if (!is_null($this->attempt)){
            $this->attempt->status = $result['_shards'];
            $this->attempt->save();
        }
    }
}
