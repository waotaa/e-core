<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\OrganisationValidation;
use Vng\EvaCore\Models\Organisation;

class OrganisationCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Organisation::class);
    }

    public function rules(): array
    {
        return OrganisationValidation::make($this)->getCreationRules();
    }
}
