<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ReleaseChangeValidation;
use Vng\EvaCore\Models\ReleaseChange;

class ReleaseChangeCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', ReleaseChange::class);
    }

    public function rules(): array
    {
        return ReleaseChangeValidation::make($this)->getCreationRules();
    }
}
