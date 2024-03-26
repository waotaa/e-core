<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ReleaseValidation;
use Vng\EvaCore\Models\Release;

class ReleaseCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Release::class);
    }

    public function rules(): array
    {
        return ReleaseValidation::make($this)->getCreationRules();
    }
}
