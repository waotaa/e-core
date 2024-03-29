<?php

namespace Vng\EvaCore\Http\Validation;

class ContactValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'phone' => [
                'regex:/(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$)/'
//                'regex:/(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)/' (allow space or dash)
            ],
            'email' => [
                'email'
            ]
        ];
    }
}
