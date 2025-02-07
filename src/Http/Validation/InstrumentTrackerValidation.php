<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Enums\FollowerRoleEnum;

class InstrumentTrackerValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'role' => [
                'required',
                'in:' . implode(',', FollowerRoleEnum::values()),
            ],
            'instrument_id' => [
                'required',
            ],
            'manager_id' => [
                'required',
            ],
        ];
    }

    protected function updateRules(Model $model): array
    {
        $rules = $this->rules();
        $rules['role'] = [
            'in:' . implode(',', FollowerRoleEnum::values()),
        ];
        return $rules;
    }
}
