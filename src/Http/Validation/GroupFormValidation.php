<?php

namespace Vng\EvaCore\Http\Validation;

class GroupFormValidation extends ModelValidation
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
