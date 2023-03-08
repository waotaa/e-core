<?php

namespace Vng\EvaCore\Http\Validation;

class ProviderValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'organisation_id' => [
                'required',
            ],
            'name' => [
                'required',
            ],
        ];
    }
}
