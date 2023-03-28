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
                'prohibited_unless:key,null',
                'max:5000',
            ],
            'key' => [
                'required_without:file',
                'prohibited_unless:file,null'
            ],
            'filename' => [
                'prohibited_unless:file,null'
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
