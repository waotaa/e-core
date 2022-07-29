<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ProfessionalValidation;
use Vng\EvaCore\Models\Professional;

class ProfessionalUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('professional'));
    }

    public function rules(): array
    {
        $professional = $this->route('professional');
        if (!$professional instanceof Professional) {
            throw new \Exception('Cannot derive professional from route');
        }
        return ProfessionalValidation::getUpdateRules($professional);
    }
}
