<?php


namespace Vng\EvaCore\Services\ElasticSearch;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;

class SyncService
{
    public static function createSyncAttempt(SearchableModel $model, string $action = null, string $status = null): SyncAttempt
    {
        $attempt = static::makeSyncAttempt($model, $action, $status);
        $attempt->save();
        return $attempt;
    }

    public static function makeSyncAttempt(SearchableModel $model, string $action = null, string $status = null): SyncAttempt
    {
        $attempt = new SyncAttempt();
        $attempt->resource()->associate($model);
        $attempt->action = $action;
        $attempt->status = $status;
        return $attempt;
    }

    public static function addRelatedModel(SyncAttempt $attempt, Model $model)
    {
        $attempt->origin()->associate($model);
        $attempt->save();
        return $attempt;
    }

    public static function updateStatus(SyncAttempt $attempt, string $status): SyncAttempt
    {
        $attempt->status = $status;
        $attempt->save();
        return $attempt;
    }
}
