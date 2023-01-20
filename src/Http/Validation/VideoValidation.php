<?php

namespace Vng\EvaCore\Http\Validation;

use Vng\EvaCore\Enums\VideoProviderEnum;

class VideoValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'provider' => [
                'in:' . implode(',', VideoProviderEnum::values()),
            ],
            'video_identifier' => [
                'max:11',
            ],
            'instrument_id' => [
                'required',
            ]
        ];
    }
}
