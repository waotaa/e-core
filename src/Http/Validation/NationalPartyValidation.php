<?php

namespace Vng\EvaCore\Http\Validation;

class NationalPartyValidation extends ModelValidation
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
