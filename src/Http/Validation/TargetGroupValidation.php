<?php

namespace Vng\EvaCore\Http\Validation;

class TargetGroupValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'description' => [
                'required'
            ]
        ];
    }
}
