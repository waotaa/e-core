<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NationalPartyValidation;
use Vng\EvaCore\Models\NationalParty;

class NationalPartyCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', NationalParty::class);
    }

    public function rules(): array
    {
        return NationalPartyValidation::make($this)->getCreationRules();
    }
}
