<?php

namespace Vng\EvaCore\Http\Validation;

class InstrumentValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'name' => [
                'required'
            ],
            'summary' => [
                'required',
                'max:500'
            ],
            'aim' => [
                'required',
            ],
            'method' => [
                'required'
            ],
            'total_costs' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'intensity_hours_per_week' => [
                'nullable',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:0'
            ],
            'organisation_id' => [
                'required'
            ],
            'provider_id' => [
                'required',
            ],
            'implementations_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'implementation_ids.*' => [
                'integer',
                'exists:implementations,id',
            ],
            'tile_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'tile_ids.*' => [
                'integer',
                'exists:tiles,id',
            ],
            'client_characteristic_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'client_characteristic_ids.*' => [
                'integer',
                'exists:client_characteristics,id',
            ],
        ];
    }
}
