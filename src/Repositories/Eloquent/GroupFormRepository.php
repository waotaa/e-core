<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Http\Requests\GroupFormCreateRequest;
use Vng\EvaCore\Http\Requests\GroupFormUpdateRequest;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Repositories\GroupFormRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class GroupFormRepository extends BaseRepository implements GroupFormRepositoryInterface
{
    public string $model = GroupForm::class;

    public function create(GroupFormCreateRequest $request): GroupForm
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(GroupForm $groupForm, GroupFormUpdateRequest $request): GroupForm
    {
        return $this->saveFromRequest($groupForm, $request);
    }

    public function saveFromRequest(GroupForm $groupForm, FormRequest $request): GroupForm
    {
        $groupForm->fill([
            'name' => $request->input('name'),
            'custom' => $request->input('custom'),
        ]);
        $groupForm->save();
        return $groupForm;
    }

    public function attachInstruments(GroupForm $groupForm, string|array $instrumentIds): GroupForm
    {
        $instrumentIds = (array) $instrumentIds;
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instrumentRepository
            ->findMany($instrumentIds)
            ->each(
                function (Instrument $instrument) use ($groupForm) {
                    Gate::authorize('attachGroupForm', [$instrument, $groupForm]);
                }
            );

        $groupForm->instruments()->syncWithoutDetaching($instrumentIds);
        return $groupForm;
    }

    public function detachInstruments(GroupForm $groupForm, string|array $instrumentIds): GroupForm
    {
        $instrumentIds = (array) $instrumentIds;
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instrumentRepository
            ->findMany($instrumentIds)
            ->each(
                function (Instrument $instrument) use ($groupForm) {
                    Gate::authorize('detachGroupForm', [$instrument, $groupForm]);
                }
            );

        $groupForm->instruments()->detach($instrumentIds);
        return $groupForm;
    }
}
