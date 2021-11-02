<?php

namespace Vng\EvaCore\Jobs;

use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;
use Elasticsearch\Client;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class RemoveResourceFromElastic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private SearchableModel $model;
    private SyncAttempt $attempt;

    public function __construct(SearchableModel $model, SyncAttempt $attempt = null)
    {
        $this->model = $model;
        if (!is_null($attempt)){
            $this->attempt = $attempt;
        }
    }

    public function handle(): void
    {
        /** @var Client $elasticsearch */
        $elasticsearch = App::make('elasticsearch');

        $index = $this->model->getSearchIndex();
        $prefix = config('elastic.prefix');

        try {
            $result = $elasticsearch->delete([
                'index' => $prefix ? $prefix.'-'.$index : $index,
                'id' => $this->model->getSearchId(),
            ]);

            if (!is_null($this->attempt)){
                $this->attempt->status = $result['_shards'];
                $this->attempt->save();
            }
        } catch (Exception $e) {
            // TODO: exception logging
        }
    }
}