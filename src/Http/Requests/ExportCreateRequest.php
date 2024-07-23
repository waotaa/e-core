<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Validation\ExportValidation;

class ExportCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return true;
//        Maybe create export policies?
//        return Auth::user()->can('create', Export::class);
    }

    public function rules(): array
    {
        return ExportValidation::make($this)->getCreationRules();
    }
}
