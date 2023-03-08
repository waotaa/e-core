<?php

namespace Vng\EvaCore\Http\Validation;

class ContactValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
            ],
            'phone' => [
                'required_without:email',
                'nullable',
                'regex:/(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$)/'
//                'regex:/(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)/' (allow space or dash)
            ],
            'email' => [
                'required_without:phone',
                'nullable',
                'email'
            ],
            'organisation_id' => [
                'required'
            ],
        ];
    }
}
