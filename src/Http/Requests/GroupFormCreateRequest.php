<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\GroupFormValidation;
use Vng\EvaCore\Models\GroupForm;

class GroupFormCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', GroupForm::class);
    }

    public function rules(): array
    {
        return GroupFormValidation::make($this)->getCreationRules();
    }
}
