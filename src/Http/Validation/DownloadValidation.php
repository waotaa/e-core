<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;

class DownloadValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'file' => [
                'required_without:key',
                'prohibited_with:key',
                'max:5000',
            ],
            'key' => [
                'required_without:file',
                'prohibited_with:file'
            ],
            'filename' => [
                'prohibited_with:file'
            ],
            'instrument_id' => [
                'required',
            ]
        ];
    }

    protected function updateRules(Model $model): array
    {
        return [
            ...$this->rules(),
            'file' => [
                'prohibited'
            ],
            'key' => [
                'prohibited'
            ]
        ];
    }
}
