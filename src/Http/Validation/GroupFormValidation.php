<?php

namespace Vng\EvaCore\Http\Validation;

class GroupFormValidation extends ModelValidation
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
