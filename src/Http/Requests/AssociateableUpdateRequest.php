<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\AssociateableValidation;
use Vng\EvaCore\Models\Associateable;

/**
 * @deprecated
 */
class AssociateableUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', 'associateable');
    }

    public function rules(): array
    {
        $associateable = $this->route('associateable');
        if (!$associateable instanceof Associateable) {
            throw new \Exception('Cannot derive associateable from route');
        }
        return AssociateableValidation::make($this)->getUpdateRules($associateable);
    }
}
