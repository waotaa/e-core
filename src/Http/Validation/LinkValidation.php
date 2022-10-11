<?php

namespace Vng\EvaCore\Http\Validation;

class LinkValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'url' => [
                'required',
                'url'
            ],
            'instrument_id' => [
                'required',
            ]
        ];
    }
}
