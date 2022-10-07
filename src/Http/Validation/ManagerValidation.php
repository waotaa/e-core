<?php

namespace Vng\EvaCore\Http\Validation;

class ManagerValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'email' => [
                'nullable',
                'email'
            ]
        ];
    }
}
