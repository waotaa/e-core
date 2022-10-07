<?php

namespace Vng\EvaCore\Http\Validation;

class ClientCharacteristicValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
        ];
    }
}
