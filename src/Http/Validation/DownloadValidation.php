<?php

namespace Vng\EvaCore\Http\Validation;

class DownloadValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'file' => [
                'required',
                'max:5000',
            ],
            'instrument' => [
                'required',
            ]
        ];
    }
}
