<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SyncAttempt extends Model
{
    use HasFactory;

    const ACTION_INDEX = 'index';
    const ACTION_ATTACH = 'attach';
    const ACTION_DETACH = 'detach';
    const ACTION_DELETE = 'delete';

    const STATUS_CREATED = 'created';
    const STATUS_STARTED = 'started';
    const STATUS_FAILED = 'failed';
    const STATUS_NO_EFFECT = 'no effect';
    const STATUS_NO_NODES = 'no nodes';
    const STATUS_SUCCESS = 'success';

    protected $fillable = [
        'action',
        'status',
        'results',
        'note'
    ];

    protected $casts = [
        'results' => 'array',
    ];

    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    public function setResourcedModel(Model $resourceModel): static
    {
        $this->resource()->associate($resourceModel);
        return $this;
    }

    public function origin(): MorphTo
    {
        return $this->morphTo();
    }

    public function setRelatedModel(Model $relatedModel): static
    {
        $this->origin()->associate($relatedModel);
        return $this;
    }

    public function updateStatus(string $status)
    {
        $this->status = $status;
        $this->save();
        return $this;
    }

    public function setNote(string $note)
    {
        $this->note = $note;
        return $this;
    }

    public function updateNote(string $note)
    {
        $this->setNote($note);
        $this->save();
        return $this;
    }

    public function addNote(string $newNote): static
    {
        $this->note = trim($this->note . "\n" . $newNote);
        $this->save();
        return $this;
    }

    public function addResults(array $newResults): static
    {
        $currentResults = $this->results ?? [];
        $updatedResults = array_merge($currentResults, $newResults);
        $this->results = $updatedResults;
        $this->save();

        return $this;
    }

    public function countFailedResults(): int
    {
        return collect($this->results)
            ->filter(fn($result) => $result === false)
            ->count();
    }

    public function successPercentage(): float
    {
        $total = count($this->results);
        if ($total === 0) {
            return 0.0;
        }

        $successful = collect($this->results)
            ->filter(fn($result) => $result === true)
            ->count();
        return ($successful / $total) * 100;
    }
}
