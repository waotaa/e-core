<?php

namespace Vng\EvaCore\Http\Validation;

class VideoValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'video_identifier' => [
                'max:11',
            ],
            'instrument_id' => [
                'required',
            ]
        ];
    }
}
