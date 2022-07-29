<?php

namespace Vng\EvaCore\Http\Validation;

class PartnershipValidation extends ModelValidation
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
