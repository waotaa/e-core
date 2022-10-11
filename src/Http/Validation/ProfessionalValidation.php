<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;

class ProfessionalValidation extends ModelValidation
{
    public static function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:254'
            ],
            'environment_id' => [
                'required'
            ]
        ];
    }

    public static function creationRules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:254',
                'unique:professionals,email'
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
                'unique:professionals,email,' . $model->id,
            ],
        ];
    }
}
