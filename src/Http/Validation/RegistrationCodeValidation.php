<?php

namespace Vng\EvaCore\Http\Validation;

class RegistrationCodeValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'label' => [
                'required',
            ],
            'code' => [
                'required',
            ],
            'is_displayed' => [
                'nullable',
                'boolean',
            ],
            'instrument_id' => [
                'required'
            ]
        ];
    }
}
