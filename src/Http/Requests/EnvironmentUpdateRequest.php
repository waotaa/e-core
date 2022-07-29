<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\EnvironmentValidation;
use Vng\EvaCore\Models\Environment;

class EnvironmentUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('environment'));
    }

    public function rules(): array
    {
        $environment = $this->route('environment');
        if (!$environment instanceof Environment) {
            throw new \Exception('Cannot derive environment from route');
        }
        return EnvironmentValidation::getUpdateRules($environment);
    }
}
