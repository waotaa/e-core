<?php

namespace Vng\EvaCore\Http\Validation;

class EnvironmentValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'name' => [
                'required'
            ]
        ];
    }
}
