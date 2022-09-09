<?php

namespace Vng\EvaCore\Http\Validation;

class RegistrationCodeValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'label' => [
                'required',
            ],
            'code' => [
                'required',
            ],
            'instrument_id' => [
                'required'
            ]
        ];
    }
}
