<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ImplementationValidation;
use Vng\EvaCore\Models\Implementation;

class ImplementationUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('implementation'));
    }

    public function rules(): array
    {
        $implementation = $this->route('implementation');
        if (!$implementation instanceof Implementation) {
            throw new \Exception('Cannot derive implementation from route');
        }
        return ImplementationValidation::make($this)->getUpdateRules($implementation);
    }
}
