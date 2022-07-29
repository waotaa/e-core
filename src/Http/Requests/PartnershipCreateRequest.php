<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NationalPartyValidation;
use Vng\EvaCore\Http\Validation\PartnershipValidation;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Partnership;

class PartnershipCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Partnership::class);
    }

    public function rules(): array
    {
        return PartnershipValidation::getCreationRules();
    }
}
