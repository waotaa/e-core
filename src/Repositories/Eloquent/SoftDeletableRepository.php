<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

trait SoftDeletableRepository
{
    public function trashed(): Collection
    {
        return $this->model::onlyTrashed()->get();
    }

    public function builderOnlyTrashed(): Builder
    {
        return $this->model::onlyTrashed();
    }

    public function findInTrashed(string $id): ?Model
    {
        return $this->model::onlyTrashed()->find($id);
    }

    public function builderWithTrashed(): Builder
    {
        return $this->model::withTrashed();
    }

    public function findWithTrashed(string $id): ?Model
    {
        return $this->model::withTrashed()->find($id);
    }

    public function restore($input): ?Model
    {
        // Controleer of het argument een Model is
        $model = $input instanceof Model
            ? $input
            : $this->findInTrashed($input);

        if (is_null($model)) {
            $id = is_string($input) ? $input : $input->getKey();
            throw new ModelNotFoundException('Model with id [' . $id . '] not found in trash');
        }

        $model->restore();
        return $model;
    }

    public function forceDelete($input): ?bool
    {
        // Controleer of het argument een Model is
        $model = $input instanceof Model
            ? $input
            : $this->findWithTrashed($input);

        if (is_null($model)) {
            $id = is_string($input) ? $input : $input->getKey();
            throw new ModelNotFoundException('Model with id [' . $id . '] not found anywhere');
        }

        return $model->forceDelete();
    }

}
