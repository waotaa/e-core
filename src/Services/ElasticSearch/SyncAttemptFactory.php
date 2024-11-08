<?php


namespace Vng\EvaCore\Services\ElasticSearch;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\SearchableModel;
use Vng\EvaCore\Models\SyncAttempt;

class SyncAttemptFactory
{
    public static function createSyncAttempt(
        string $action = null,
        SearchableModel $resourceModel = null,
        Model $relatedModel = null,
    ): SyncAttempt
    {
        $attempt = static::makeSyncAttempt(
            $action,
            $resourceModel,
            $relatedModel
        );
        $attempt->save();
        return $attempt;
    }

    public static function makeSyncAttempt(
        string $action = null,
        SearchableModel $resourceModel = null,
        Model $relatedModel = null,
    ): SyncAttempt
    {
        $attempt = new SyncAttempt();
        $attempt->status = SyncAttempt::STATUS_CREATED;

        if ($resourceModel) {
            $attempt->setResourcedModel($resourceModel);
        }
        if ($relatedModel) {
            $attempt->setRelatedModel($relatedModel);
        }
        $attempt->action = $action;

        return $attempt;
    }
}
