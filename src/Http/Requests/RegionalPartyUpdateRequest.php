<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\RegionalPartyValidation;
use Vng\EvaCore\Models\RegionalParty;

class RegionalPartyUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('regionalParty'));
    }

    public function rules(): array
    {
        $regionalParty = $this->route('regionalParty');
        if (!$regionalParty instanceof RegionalParty) {
            throw new \Exception('Cannot derive regionalParty from route');
        }
        return RegionalPartyValidation::make($this)->getUpdateRules($regionalParty);
    }
}
