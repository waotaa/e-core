<?php

namespace Vng\EvaCore\Http\Validation;

class FaqValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'type' => 'required|string|in:portaal,beheer',
            'category' => 'required|string|max:255',
        ];
    }
}
