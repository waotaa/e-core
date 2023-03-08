<?php

namespace Vng\EvaCore\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Vng\EvaCore\Observers\AddressObserver;

class Mutation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'original' => 'array',
        'changes' => 'array',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function target()
    {
        return $this->morphTo('target', 'target_type', 'target_id')->withTrashed();
    }

    public static function create($name, Manager $manager, $loggable, $target = null, $model = null): Mutation
    {
        if (is_null($target)) {
            $target = $loggable;
        }
        if (is_null($model)) {
            $model = $loggable;
        }
        return new static([
            'batch_id' => (string) Str::orderedUuid(),
            'manager_id' => $manager->getKey(),
            'name' => $name,

            'loggable_type' => $loggable->getMorphClass(),
            'loggable_id' => $loggable->getKey(),
            'target_type' => $target->getMorphClass(),
            'target_id' => $target->getKey(),
            'model_type' => $model->getMorphClass(),
            'model_id' => $model->getKey(),

            'fields' => '',
            'original' => null,
            'changes' => null,
            'status' => 'finished',
            'exception' => '',
        ]);
    }

    public static function forResourceCreate(Manager $manager, $model): Mutation
    {
        return static::create('Create', $manager, $model)->fill([
            'changes' => $model->attributesToArray(),
        ]);
    }

    public static function forResourceUpdate(Manager $manager, $model): Mutation
    {
        return static::create('Update', $manager, $model)->fill([
            'original' => array_intersect_key($model->getRawOriginal(), $model->getDirty()),
            'changes' => $model->getDirty(),
        ]);
    }

    public static function forAttachedResource(Manager $manager, $parent, $target, $pivot): Mutation
    {
        return static::create('Attach', $manager, $parent, $target, $pivot)->fill([
            'changes' => $pivot->attributesToArray(),
        ]);
    }

    public static function forAttachedResourceUpdate(Manager $manager, $parent, $target, $pivot): Mutation
    {
        return static::create('Update Attached', $manager, $parent, $target, $pivot)->fill([
            'original' => array_intersect_key($pivot->getRawOriginal(), $pivot->getDirty()),
            'changes' => $pivot->getDirty(),
        ]);
    }

    public static function forResourceDelete(Manager $manager, Collection $models): Collection
    {
        return static::forSoftDeleteAction('Delete', $manager, $models);
    }

    public static function forResourceRestore(Manager $manager, Collection $models): Collection
    {
        return static::forSoftDeleteAction('Restore', $manager, $models);
    }

    public static function forSoftDeleteAction(string $action, Manager $manager, Collection $models): Collection
    {
        $batchId = (string) Str::orderedUuid();
        return $models->map(function ($model) use ($action, $manager, $batchId) {
            return static::create($action, $manager, $model)->fill([
                'batch_id' => $batchId,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ]);
        });
    }

    public static function forResourceDetach(Manager $manager, $parent, Collection $models, $pivotClass): Collection
    {
        $batchId = (string) Str::orderedUuid();
        return $models->map(function ($model) use ($manager, $parent, $pivotClass, $batchId) {
            return static::create('Detach', $manager, $parent, $model)->fill([
                'batch_id' => $batchId,
                'model_type' => $pivotClass,
                'model_id' => null,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ]);
        });
    }

    public static function markBatchAsRunning($batchId): int
    {
        return static::where('batch_id', $batchId)
            ->whereNotIn('status', ['finished', 'failed'])->update([
                'status' => 'running',
            ]);
    }

    public static function markBatchAsFinished($batchId): int
    {
        return static::where('batch_id', $batchId)
            ->whereNotIn('status', ['finished', 'failed'])->update([
                'status' => 'finished',
            ]);
    }

    public static function markAsFinished($batchId, $model)
    {
        return static::updateStatus($batchId, $model, 'finished');
    }

    public static function markBatchAsFailed($batchId, $e = null)
    {
        return static::where('batch_id', $batchId)
            ->whereNotIn('status', ['finished', 'failed'])->update([
                'status' => 'failed',
                'exception' => $e ? (string) $e : '',
            ]);
    }

    public static function markAsFailed($batchId, $model, $e = null)
    {
        return static::updateStatus($batchId, $model, 'failed', $e);
    }

    public static function updateStatus($batchId, $model, $status, $e = null)
    {
        return static::where('batch_id', $batchId)
            ->where('model_type', $model->getMorphClass())
            ->where('model_id', $model->getKey())
            ->update(['status' => $status, 'exception' => (string) $e]);
    }
}
