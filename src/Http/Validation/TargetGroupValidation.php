<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Validation\Rule;

class TargetGroupValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'description' => [
                'required',
                Rule::unique('target_groups', 'description')
            ]
        ];
    }
}
