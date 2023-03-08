<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\TargetGroupCreateRequest;
use Vng\EvaCore\Http\Requests\TargetGroupUpdateRequest;
use Vng\EvaCore\Models\TargetGroup;

interface TargetGroupRepositoryInterface extends BaseRepositoryInterface
{
    public function create(TargetGroupCreateRequest $request): TargetGroup;
    public function update(TargetGroup $targetGroup, TargetGroupUpdateRequest $request): TargetGroup;

    public function attachInstruments(TargetGroup $targetGroup, string|array $instrumentIds): TargetGroup;
    public function detachInstruments(TargetGroup $targetGroup, string|array $instrumentIds): TargetGroup;
}
