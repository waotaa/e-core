<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

abstract class BaseFormRequest extends FormRequest
{
    public function withValidator(Validator $validator): void
    {
        if (method_exists($this, 'after')) {
            $validator->after([$this, 'after']);
        }
    }
}
