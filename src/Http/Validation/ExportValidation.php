<?php

namespace Vng\EvaCore\Http\Validation;

class ExportValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'label' => [
                'nullable',
                'string'
            ],
            'organisation_id' => [
                'required'
            ],
        ];
    }
}
