<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ProfessionalValidation extends ModelValidation
{
    public function rules(): array
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

    protected function creationRules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:254',
                Rule::unique('professionals', 'email')
                    ->where('environment_id', $this->request->input('environment_id'))
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
                Rule::unique('professionals', 'email')
                    ->where('environment_id', $this->request->input('environment_id'))
                    ->ignore($model->id)
            ],
        ];
    }
}
