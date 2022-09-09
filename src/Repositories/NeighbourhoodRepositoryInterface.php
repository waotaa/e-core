<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\NeighbourhoodCreateRequest;
use Vng\EvaCore\Http\Requests\NeighbourhoodUpdateRequest;
use Vng\EvaCore\Models\Neighbourhood;

interface NeighbourhoodRepositoryInterface extends BaseRepositoryInterface
{
    public function create(NeighbourhoodCreateRequest $request): Neighbourhood;
    public function update(Neighbourhood $neighbourhood, NeighbourhoodUpdateRequest $request): Neighbourhood;
}
