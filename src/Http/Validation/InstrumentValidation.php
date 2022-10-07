<?php

namespace Vng\EvaCore\Http\Validation;

class InstrumentValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'organisation_id' => [
                'required'
            ],
            'name' => [
                'required'
            ],
            'summary' => [
                'required',
                'max:500'
            ],
            'provider_id' => [
                'required',
            ],
            'aim' => [
                'required',
            ],
            'implementation_id' => [
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
        ];
    }
}
