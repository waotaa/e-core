<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ImplementationValidation;
use Vng\EvaCore\Models\Implementation;

class ImplementationCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Implementation::class);
    }

    public function rules(): array
    {
        return ImplementationValidation::getCreationRules();
    }
}
