<?php

namespace Vng\EvaCore\Http\Validation;

class ReleaseChangeValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
            ],
            'description' => [
                'nullable',
                'max:500',
            ],
            'image' => [
                'nullable',
                'max:1500',
            ],
            'release_id' => [
                'required'
            ],
        ];
    }
}
