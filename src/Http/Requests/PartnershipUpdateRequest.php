<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\PartnershipValidation;
use Vng\EvaCore\Models\Partnership;

class PartnershipUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('partnership'));
    }

    public function rules(): array
    {
        $partnership = $this->route('partnership');
        if (!$partnership instanceof Partnership) {
            throw new \Exception('Cannot derive partnership from route');
        }
        return PartnershipValidation::make($this)->getUpdateRules($partnership);
    }
}
