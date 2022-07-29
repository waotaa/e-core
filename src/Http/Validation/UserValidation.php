<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;

class UserValidation extends ModelValidation
{
    public static function rules(): array
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

    public static function creationRules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:254',
                'unique:users,email'
            ],
        ];
    }

    public static function updateRules(Model $model): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:254',
                'unique:users,email,' . $model->id,
            ],
        ];
    }
}
