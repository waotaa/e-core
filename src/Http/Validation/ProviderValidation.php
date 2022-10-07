<?php

namespace Vng\EvaCore\Http\Validation;

class ProviderValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'organisation_id' => [
                'required',
            ],
            'name' => [
                'required',
            ],
        ];
    }
}
