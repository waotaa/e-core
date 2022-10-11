<?php

namespace Vng\EvaCore\Http\Validation;

class InstrumentValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'name' => [
                'required'
            ],
            'summary' => [
                'required',
                'max:500'
            ],
            'aim' => [
                'required',
            ],
            'method' => [
                'required'
            ],
            'total_costs' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'organisation_id' => [
                'required'
            ],
            'provider_id' => [
                'required',
            ],
            'implementation_id' => [
                'required',
            ],
        ];
    }
}
