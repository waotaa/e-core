<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class UserValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:254',
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
            ],
            'months_unupdated_limit' => [
                'nullable',
                'numeric',
                'min:0'
            ],
        ];
    }

    protected function creationRules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:254',
                Rule::unique('users', 'email')
            ],
        ];
    }

    protected function updateRules(Model $model): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:254',
                Rule::unique('users', 'email')
                    ->ignore($model->id)
            ],
        ];
    }
}
