<?php

namespace Vng\EvaCore\Http\Validation;

use Vng\EvaCore\Enums\FollowerRoleEnum;

class InstrumentTrackerValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'role' => [
                'in:' . implode(',', FollowerRoleEnum::keys()),
            ],
            'instrument_id' => [
                'required',
            ],
            'manager_id' => [
                'required',
            ],
        ];
    }
}
