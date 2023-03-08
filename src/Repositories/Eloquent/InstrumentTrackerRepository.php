<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\InstrumentTrackerCreateRequest;
use Vng\EvaCore\Http\Requests\InstrumentTrackerUpdateRequest;
use Vng\EvaCore\Http\Requests\LocalPartyCreateRequest;
use Vng\EvaCore\Http\Requests\LocalPartyUpdateRequest;
use Vng\EvaCore\Models\InstrumentTracker;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Repositories\InstrumentTrackerRepositoryInterface;
use Vng\EvaCore\Repositories\LocalPartyRepositoryInterface;

class InstrumentTrackerRepository extends BaseRepository implements InstrumentTrackerRepositoryInterface
{
    use InstrumentOwnedEntityRepository;

    protected string $model = InstrumentTracker::class;

    public function new(): InstrumentTracker
    {
        return new $this->model;
    }

    public function create(InstrumentTrackerCreateRequest $request): InstrumentTracker
    {
        return $this->saveFromRequest($this->new(), $request);
    }

    public function update(InstrumentTracker $instrumentTracker, InstrumentTrackerUpdateRequest $request): InstrumentTracker
    {
        return $this->saveFromRequest($instrumentTracker, $request);
    }

    public function saveFromRequest(InstrumentTracker $instrumentTracker, FormRequest $request): InstrumentTracker
    {
        $instrumentTracker = $instrumentTracker->fill([
            'role' => $request->input('role'),
            'notification_frequency' => $request->input('notification_frequency'),
            'on_modification' => $request->input('on_modification'),
            'on_expiration' => $request->input('on_expiration'),
        ]);
        $instrumentTracker->instrument()->associate($request->input('instrument_id'));
        $instrumentTracker->manager()->associate($request->input('manager_id'));

        $instrumentTracker->save();
        return $instrumentTracker;
    }
}
