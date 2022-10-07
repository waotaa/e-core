<?php

namespace Vng\EvaCore\Http\Validation;

class LinkValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'url' => [
                'url'
            ],
            'instrument_id' => [
                'required',
            ]
        ];
    }
}
