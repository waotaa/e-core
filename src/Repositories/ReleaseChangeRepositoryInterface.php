<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ReleaseChangeCreateRequest;
use Vng\EvaCore\Http\Requests\ReleaseChangeUpdateRequest;
use Vng\EvaCore\Models\ReleaseChange;

interface ReleaseChangeRepositoryInterface extends BaseRepositoryInterface
{
    public function create(ReleaseChangeCreateRequest $request): ReleaseChange;
    public function update(ReleaseChange $releaseChange, ReleaseChangeUpdateRequest $request): ReleaseChange;
}
