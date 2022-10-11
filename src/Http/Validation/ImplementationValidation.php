<?php

namespace Vng\EvaCore\Http\Validation;

class ImplementationValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'name' => [
                'required'
            ]
        ];
    }
}
