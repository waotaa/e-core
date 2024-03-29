<?php

namespace Vng\EvaCore\Http\Validation;

class TileValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'sub_title' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'list' => [
                'required',
            ],
        ];
    }
}
