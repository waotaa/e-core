<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\InstrumentTypeCreateRequest;
use Vng\EvaCore\Http\Requests\InstrumentTypeUpdateRequest;
use Vng\EvaCore\Models\InstrumentType;
use Vng\EvaCore\Repositories\InstrumentTypeRepositoryInterface;

class InstrumentTypeRepository extends BaseRepository implements InstrumentTypeRepositoryInterface
{
    protected string $model = InstrumentType::class;

    public function new(): InstrumentType
    {
        return new $this->model;
    }

    public function create(InstrumentTypeCreateRequest $request): InstrumentType
    {
        return $this->saveFromRequest($this->new(), $request);
    }

    public function update(InstrumentType $instrumentType, InstrumentTypeUpdateRequest $request): InstrumentType
    {
        return $this->saveFromRequest($instrumentType, $request);
    }

    public function saveFromRequest(InstrumentType $instrumentType, FormRequest $request): InstrumentType
    {
        $instrumentType = $instrumentType->fill([
            'name' => $request->input('name'),
            'key' => $request->input('key'),
        ]);
        
        $instrumentType->save();
        return $instrumentType;
    }

    public function attachInstruments(InstrumentType $instrumentType, string|array $instrumentIds): InstrumentType
    {
        $instrumentType->instruments()->syncWithoutDetaching((array) $instrumentIds);
        return $instrumentType;
    }

    public function detachInstruments(InstrumentType $instrumentType, string|array $instrumentIds): InstrumentType
    {
        $instrumentType->instruments()->detach((array) $instrumentIds);
        return $instrumentType;
    }
}
