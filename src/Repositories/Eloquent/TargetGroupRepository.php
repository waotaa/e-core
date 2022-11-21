<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\TargetGroupCreateRequest;
use Vng\EvaCore\Http\Requests\TargetGroupUpdateRequest;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Repositories\TargetGroupRepositoryInterface;

class TargetGroupRepository extends BaseRepository implements TargetGroupRepositoryInterface
{
    public string $model = TargetGroup::class;

    public function create(TargetGroupCreateRequest $request): TargetGroup
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(TargetGroup $targetGroup, TargetGroupUpdateRequest $request): TargetGroup
    {
        return $this->saveFromRequest($targetGroup, $request);
    }

    public function saveFromRequest(TargetGroup $targetGroup, FormRequest $request): TargetGroup
    {
        $targetGroup->fill([
            'description' => $request->input('description'),
            'custom' => $request->input('custom'),
        ]);

        $targetGroup->save();
        return $targetGroup;
    }

    public function attachInstruments(TargetGroup $targetGroup, string|array $instrumentIds): TargetGroup
    {
        $targetGroup->instruments()->syncWithoutDetaching((array) $instrumentIds);
        return $targetGroup;
    }

    public function detachInstruments(TargetGroup $targetGroup, string|array $instrumentIds): TargetGroup
    {
        $targetGroup->instruments()->detach((array) $instrumentIds);
        return $targetGroup;
    }
}
