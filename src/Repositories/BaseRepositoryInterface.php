<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function builder(): Builder;

    public function all(): Collection;

    public function index(int $perPage = 10): LengthAwarePaginator;

    public function find(string $id): ?Model;

    public function new(): Model;

    public function delete(string $id): ?bool;
}
