<?php

namespace Vng\EvaCore\Http\Validation;

use Vng\EvaCore\Enums\LocationEnum;

class LocationValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'type' => [
                'in:' . implode(',', LocationEnum::keys()),
                'nullable'
            ],
            'is_active' => [
                'boolean'
            ],
            'address_id' => [
                'required_if:type,Adres',
                'prohibited_if:type,Klant thuis'
            ],
            'instrument_id' => [
                'required'
            ]
        ];
    }
}
