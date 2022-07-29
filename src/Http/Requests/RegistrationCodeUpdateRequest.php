<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\RegistrationCodeValidation;
use Vng\EvaCore\Models\RegistrationCode;

class RegistrationCodeUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('registrationCode'));
    }

    public function rules(): array
    {
        $registrationCode = $this->route('registrationCode');
        if (!$registrationCode instanceof RegistrationCode) {
            throw new \Exception('Cannot derive registrationCode from route');
        }
        return RegistrationCodeValidation::getUpdateRules($registrationCode);
    }
}
