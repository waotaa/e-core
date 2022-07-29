<?php

namespace Vng\EvaCore\Http\Validation;

class AddressValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'postcode' => [
                'required',
                'regex:/^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i'
            ],
            'woonplaats' => [
                'required'
            ]
        ];
    }
}
