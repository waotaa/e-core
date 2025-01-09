<?php

namespace Vng\EvaCore\Http\Validation;

use Illuminate\Database\Eloquent\Model;

class DownloadValidation extends ModelValidation
{
    public function rules(): array
    {
        return [
            'label' => [
                'required'
            ],
            // default upload method
            'file' => [
                'required_without:key',
                'prohibited_unless:key,null',
                'max:5000',
            ],
            // multipart upload method
            'key' => [
                'required_without:file',
                'prohibited_unless:file,null'
            ],
            // multipart upload method
            'filename' => [
                'prohibited_unless:file,null'
            ],
            'organisation_id' => [
                'required'
            ],
        ];
    }

    protected function updateRules(Model $model): array
    {
        $rules = $this->rules();

        // Pas bestaande regels aan
        $rules['file'] = [
            'nullable', // nullable, not required when key is missing
            'prohibited_unless:key,null',
            'max:5000',
        ];
        $rules['key'] = [
            'nullable', // nullable, not required when file is missing
            'prohibited_unless:file,null'
        ];

        return $rules;
    }
}
