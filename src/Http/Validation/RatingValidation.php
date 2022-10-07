<?php

namespace Vng\EvaCore\Http\Validation;

class RatingValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'instrument_id' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
            ],
            'general_score' => [
                'required',
                'between:1,5',
            ],
            'general_explanation' => [
                'requiredIf:general_score,1,5',
            ],
            'result_score' => [
                'required',
                'between:1,5',
            ],
            'result_explanation' => [
                'requiredIf:general_score,1,5',
            ],
            'execution_score' => [
                'required',
                'between:1,5',
            ],
            'execution_explanation' => [
                'requiredIf:general_score,1,5',
            ],
        ];
    }
}
