<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\LocalPartyValidation;
use Vng\EvaCore\Models\LocalParty;

class LocalPartyCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', LocalParty::class);
    }

    public function rules(): array
    {
        return LocalPartyValidation::getCreationRules();
    }
}
