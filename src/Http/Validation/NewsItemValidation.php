<?php

namespace Vng\EvaCore\Http\Validation;

class NewsItemValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'title' => [
                'required',
            ],
            'body' => [
                'required',
            ],
            'environment_id' => [
                'required',
            ],
        ];
    }
}
