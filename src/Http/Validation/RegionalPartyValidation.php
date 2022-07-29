<?php

namespace Vng\EvaCore\Http\Validation;

class RegionalPartyValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'region_id' => [
                'required',
            ],
        ];
    }
}
