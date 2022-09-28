<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SoftDeletableRepositoryInterface
{
    public function trashed(): Collection;
    public function findInTrashed(string $id): ?Model;
    public function findWithTrashed(string $id): ?Model;
    public function restore(string $id): ?Model;
    public function forceDelete(string $id): ?bool;
}
