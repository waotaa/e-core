<?php

namespace Vng\EvaCore\Http\Validation;

class ReleaseValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'version' => [
                'required',
                'regex:/^\d+\.\d+\.\d+$/'
            ],
        ];
    }
}
