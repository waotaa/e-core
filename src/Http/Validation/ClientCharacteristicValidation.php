<?php

namespace Vng\EvaCore\Http\Validation;

class ClientCharacteristicValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
        ];
    }
}
