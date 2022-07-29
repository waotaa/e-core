<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function builder(): Builder;

    public function all(): Collection;

    public function find(string $id): ?Model;

    public function new(): Model;

    public function delete(string $id): ?bool;
}
