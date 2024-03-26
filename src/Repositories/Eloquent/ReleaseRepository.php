<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\ReleaseCreateRequest;
use Vng\EvaCore\Http\Requests\ReleaseUpdateRequest;
use Vng\EvaCore\Models\Release;
use Vng\EvaCore\Repositories\ReleaseRepositoryInterface;

class ReleaseRepository extends BaseRepository implements ReleaseRepositoryInterface
{
    public string $model = Release::class;

    public function create(ReleaseCreateRequest $request): Release
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Release $release, ReleaseUpdateRequest $request): Release
    {
        return $this->saveFromRequest($release, $request);
    }

    public function saveFromRequest(Release $release, FormRequest $request): Release
    {
        $release->fill([
            'version' => $request->input('version'),
            'changes' => $request->input('changes'),
            'planned_at' => $request->input('planned_at'),
        ]);

        $release->save();
        return $release;
    }

    public function getNextPlannedRelease(): ?Release
    {
        /** @var ?Release $release */
        $release = $this->builder()
            ->where('planned_at', '>', Carbon::now())
            ->orderBy('planned_at', 'asc')
            ->first();
        return $release;
    }

    public function getLastReleased(): ?Release
    {
        /** @var ?Release $release */
        $release = $this->builder()
            ->whereNotNull('released_at')
            ->where('released_at', '<=', Carbon::now())
            ->orderBy('released_at', 'desc')
            ->first();
        return $release;
    }

    public function markReleased(int $releaseId): bool
    {
        $release = $this->find($releaseId);
        if ($release) {
            $release->released_at = Carbon::now();
            return $release->save();
        }

        return false;
    }
}
