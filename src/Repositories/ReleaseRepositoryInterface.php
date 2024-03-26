<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ReleaseCreateRequest;
use Vng\EvaCore\Http\Requests\ReleaseUpdateRequest;
use Vng\EvaCore\Models\Release;

interface ReleaseRepositoryInterface extends BaseRepositoryInterface
{
    public function create(ReleaseCreateRequest $request): Release;
    public function update(Release $release, ReleaseUpdateRequest $request): Release;

    public function getNextPlannedRelease(): ?Release;
    public function getLastReleased(): ?Release;
    public function markReleased(int $releaseId): bool;
}
