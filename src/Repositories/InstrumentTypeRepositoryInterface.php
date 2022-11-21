<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\InstrumentTypeCreateRequest;
use Vng\EvaCore\Http\Requests\InstrumentTypeUpdateRequest;
use Vng\EvaCore\Models\InstrumentType;

interface InstrumentTypeRepositoryInterface extends BaseRepositoryInterface
{
    public function create(InstrumentTypeCreateRequest $request): InstrumentType;
    public function update(InstrumentType $instrumentType, InstrumentTypeUpdateRequest $request): InstrumentType;

    public function attachInstruments(InstrumentType $instrumentType, string|array $instrumentIds): InstrumentType;
    public function detachInstruments(InstrumentType $instrumentType, string|array $instrumentIds): InstrumentType;
}
