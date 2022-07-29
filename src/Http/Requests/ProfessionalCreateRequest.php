<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ProfessionalValidation;
use Vng\EvaCore\Models\Professional;

class ProfessionalCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Professional::class);
    }

    public function rules(): array
    {
        return ProfessionalValidation::getCreationRules();
    }
}
