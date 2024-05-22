<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Vng\EvaCore\Http\Validation\Rule\SoftDeletedRule;

class TargetGroupValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'organisation_id' => [
                'required'
            ],
        ];
    }

    protected function creationRules(): array
    {
        return [
            'description' => [
                'required',
                Rule::unique('target_groups', 'description')
                    ->where('organisation_id', $this->request->input('organisation_id'))
                    ->withoutTrashed(),
                (new SoftDeletedRule('description'))
                    ->where('organisation_id', $this->request->input('organisation_id'))
            ]
        ];
    }

    protected function updateRules(Model $model): array
    {
        return [
            'description' => [
                'required',
                Rule::unique('target_groups', 'description')
                    ->where('organisation_id', $this->request->input('organisation_id'))
                    ->ignore($model->id),
                (new SoftDeletedRule('description'))
                    ->where('organisation_id', $this->request->input('organisation_id'))
                    ->ignore($model->id)
            ]
        ];
    }
}
