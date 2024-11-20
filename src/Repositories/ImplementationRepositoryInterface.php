<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ImplementationCreateRequest;
use Vng\EvaCore\Http\Requests\ImplementationUpdateRequest;
use Vng\EvaCore\Models\Implementation;

interface ImplementationRepositoryInterface extends BaseRepositoryInterface
{
    public function create(ImplementationCreateRequest $request): Implementation;
    public function update(Implementation $implementation, ImplementationUpdateRequest $request): Implementation;

    public function attachInstruments(Implementation $implementation, string|array $instrumentIds): Implementation;
    public function detachInstruments(Implementation $implementation, string|array $instrumentIds): Implementation;
}
