<?php

namespace Vng\EvaCore\Http\Validation;

class EnvironmentValidation extends ModelValidation
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
