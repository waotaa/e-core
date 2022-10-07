<?php

namespace Vng\EvaCore\Http\Validation;

class NeighbourhoodValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'township_id' => [
                'required',
            ],
        ];
    }
}
