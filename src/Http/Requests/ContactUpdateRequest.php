<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ContactValidation;
use Vng\EvaCore\Models\Contact;

class ContactUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('contact'));
    }

    public function rules(): array
    {
        $contact = $this->route('contact');
        if (!$contact instanceof Contact) {
            throw new \Exception('Cannot derive contact from route');
        }
        return ContactValidation::make($this)->getUpdateRules($contact);
    }
}
