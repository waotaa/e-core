<?php

namespace Vng\EvaCore\Http\Validation;

class LocationValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
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
