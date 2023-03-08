<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Http\Requests\ClientCharacteristicCreateRequest;
use Vng\EvaCore\Http\Requests\ClientCharacteristicUpdateRequest;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Repositories\ClientCharacteristicRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class ClientCharacteristicRepository extends BaseRepository implements ClientCharacteristicRepositoryInterface
{
    public string $model = ClientCharacteristic::class;

    public function create(ClientCharacteristicCreateRequest $request): ClientCharacteristic
    {
        Gate::authorize('create', ClientCharacteristic::class);
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(ClientCharacteristic $clientCharacteristic, ClientCharacteristicUpdateRequest $request): ClientCharacteristic
    {
        Gate::authorize('update', $clientCharacteristic);
        return $this->saveFromRequest($clientCharacteristic, $request);
    }

    public function saveFromRequest(ClientCharacteristic $clientCharacteristic, FormRequest $request): ClientCharacteristic
    {
        $clientCharacteristic->fill([
            'name' => $request->input('name'),
        ]);
        $clientCharacteristic->save();
        return $clientCharacteristic;
    }

    public function attachInstruments(ClientCharacteristic $clientCharacteristic, array|string $instrumentIds): ClientCharacteristic
    {
        $instrumentIds = (array) $instrumentIds;
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instrumentRepository
            ->findMany($instrumentIds)
            ->each(
                function (Instrument $instrument) use ($clientCharacteristic) {
                    Gate::authorize('attachInstrument', [$clientCharacteristic, $instrument]);
                }
            );

        $clientCharacteristic->instruments()->syncWithoutDetaching($instrumentIds);
        return $clientCharacteristic;
    }

    public function detachInstruments(ClientCharacteristic $clientCharacteristic, array|string $instrumentIds): ClientCharacteristic
    {
        $instrumentIds = (array) $instrumentIds;
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instrumentRepository
            ->findMany($instrumentIds)
            ->each(
                function (Instrument $instrument) use ($clientCharacteristic) {
                    Gate::authorize('detachInstrument', [$clientCharacteristic, $instrument]);
                }
            );

        $clientCharacteristic->instruments()->detach($instrumentIds);
        return $clientCharacteristic;
    }
}
