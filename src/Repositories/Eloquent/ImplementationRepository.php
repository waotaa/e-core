<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Http\Requests\ImplementationCreateRequest;
use Vng\EvaCore\Http\Requests\ImplementationUpdateRequest;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Repositories\ImplementationRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class ImplementationRepository extends BaseRepository implements ImplementationRepositoryInterface
{
    public string $model = Implementation::class;

    public function create(ImplementationCreateRequest $request): Implementation
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Implementation $implementation, ImplementationUpdateRequest $request): Implementation
    {
        return $this->saveFromRequest($implementation, $request);
    }

    public function saveFromRequest(Implementation $implementation, FormRequest $request): Implementation
    {
        $implementation->fill([
            'name' => $request->input('name'),
            'custom' => $request->input('custom'),
        ]);
        $implementation->save();
        return $implementation;
    }

    public function attachInstruments(Implementation $implementation, string|array $instrumentIds): Implementation
    {
        $instrumentIds = (array) $instrumentIds;
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instrumentRepository
            ->findMany($instrumentIds)
            ->each(
                function (Instrument $instrument) use ($implementation) {
                    Gate::authorize('attachImplementation', [$instrument, $implementation]);
                }
            );

        $implementation->instruments()->syncWithoutDetaching($instrumentIds);
        return $implementation;
    }

    public function detachInstruments(Implementation $implementation, string|array $instrumentIds): Implementation
    {
        $instrumentIds = (array) $instrumentIds;
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instrumentRepository
            ->findMany($instrumentIds)
            ->each(
                function (Instrument $instrument) use ($implementation) {
                    Gate::authorize('detachImplementation', [$instrument, $implementation]);
                }
            );

        $implementation->instruments()->detach($instrumentIds);
        return $implementation;
    }
}
