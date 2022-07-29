<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ContactValidation;
use Vng\EvaCore\Models\Contact;

class ContactCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Contact::class);
    }

    public function rules(): array
    {
        return ContactValidation::getCreationRules();
    }
}
