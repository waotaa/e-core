<?php

namespace Vng\EvaCore\Http\Validation;

class LinkValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'label' => [
                'required'
            ],
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
