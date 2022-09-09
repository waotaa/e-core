<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\EnvironmentValidation;
use Vng\EvaCore\Models\Environment;

class EnvironmentCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Environment::class);
    }

    public function rules(): array
    {
        return EnvironmentValidation::getCreationRules();
    }
}
