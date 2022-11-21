<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

trait SoftDeletableRepository
{
    public function trashed(): Collection
    {
        return $this->model::onlyTrashed()->get();
    }

    public function findInTrashed(string $id): ?Model
    {
        return $this->model::onlyTrashed()->find($id);
    }

    public function findWithTrashed(string $id): ?Model
    {
        return $this->model::withTrashed()->find($id);
    }

    public function restore(string $id): ?Model
    {
        $model = $this->findInTrashed($id);
        if (is_null($model)) {
            throw new ModelNotFoundException('Model with id [' . $id . '] not found in trash');
        }
        $model->restore();
        return $model;
    }

    public function forceDelete(string $id): ?bool
    {
        $model = $this->findWithTrashed($id);
        if (is_null($model)) {
            throw new ModelNotFoundException('Model with id [' . $id . '] not found anywhere');
        }
        return $model->forceDelete();
    }
}
