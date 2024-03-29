<?php

namespace Vng\EvaCore\Http\Validation;

class LocationValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'address' => [
                'required_if:type,Adres',
                'prohibited_if:type,Klant thuis'
            ]
        ];
    }
}
