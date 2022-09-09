<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ClientCharacteristicCreateRequest;
use Vng\EvaCore\Http\Requests\ClientCharacteristicUpdateRequest;
use Vng\EvaCore\Models\ClientCharacteristic;

interface ClientCharacteristicRepositoryInterface extends BaseRepositoryInterface
{
    public function create(ClientCharacteristicCreateRequest $request): ClientCharacteristic;
    public function update(ClientCharacteristic $clientCharacteristic, ClientCharacteristicUpdateRequest $request): ClientCharacteristic;

    public function attachInstruments(ClientCharacteristic $clientCharacteristic, string|array $instrumentIds): ClientCharacteristic;
    public function detachInstruments(ClientCharacteristic $clientCharacteristic, string|array $instrumentIds): ClientCharacteristic;
}
