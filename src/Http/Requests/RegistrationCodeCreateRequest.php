<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\RegistrationCodeValidation;
use Vng\EvaCore\Models\RegistrationCode;

class RegistrationCodeCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', RegistrationCode::class);
    }

    public function rules(): array
    {
        return RegistrationCodeValidation::make($this)->getCreationRules();
    }
}
