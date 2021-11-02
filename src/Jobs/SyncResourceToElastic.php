<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;
use Elasticsearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class SyncResourceToElastic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private SearchableModel $model;
    private ?SyncAttempt $attempt;

    public function __construct(SearchableModel $model, SyncAttempt $attempt = null)
    {
        $this->model = $model;
        $this->attempt = $attempt;
    }

    public function handle(): void
    {
        /** @var Client $elastic */
        $elasticsearch = App::make('elasticsearch');

        $index = $this->model->getSearchIndex();
        $prefix = config('elastic.prefix');

        $result = $elasticsearch->index([
            'index' => $prefix ? $prefix.'-'.$index : $index,
            'type' => $this->model->getSearchType(),
            'id' => $this->model->getSearchId(),
            'body' => $this->model->toSearchArray(),
        ]);

        if (!is_null($this->attempt)){
            $this->attempt->status = $result['_shards'];
//            $this->attempt->status = $result['_shards']['failed'] === 0 ? 'succes' : 'failed';
            $this->attempt->save();
        }
    }
}