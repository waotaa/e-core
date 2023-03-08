<?php

namespace Vng\EvaCore\Http\Validation;

class PartnershipValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
        ];
    }
}
