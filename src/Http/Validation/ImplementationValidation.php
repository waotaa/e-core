<?php

namespace Vng\EvaCore\Http\Validation;

class ImplementationValidation extends ModelValidation
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
