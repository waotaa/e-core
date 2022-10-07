<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\ClientCharacteristicCreateRequest;
use Vng\EvaCore\Http\Requests\ClientCharacteristicUpdateRequest;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Repositories\ClientCharacteristicRepositoryInterface;

class ClientCharacteristicRepository extends BaseRepository implements ClientCharacteristicRepositoryInterface
{
    public string $model = ClientCharacteristic::class;

    public function create(ClientCharacteristicCreateRequest $request): ClientCharacteristic
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(ClientCharacteristic $clientCharacteristic, ClientCharacteristicUpdateRequest $request): ClientCharacteristic
    {
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
        $clientCharacteristic->instruments()->syncWithoutDetaching((array) $instrumentIds);
        return $clientCharacteristic;
    }

    public function detachInstruments(ClientCharacteristic $clientCharacteristic, array|string $instrumentIds): ClientCharacteristic
    {
        $clientCharacteristic->instruments()->detach((array) $instrumentIds);
        return $clientCharacteristic;
    }
}
