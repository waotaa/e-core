<?php

namespace Vng\EvaCore\Http\Validation;

class AssociateableValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'user_id' => [
                'required'
            ],
            'assoociateable_type' => [
                'required'
            ],
            'assoociateable_id' => [
                'required'
            ],
        ];
    }
}
