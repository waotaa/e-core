<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\LinkValidation;
use Vng\EvaCore\Models\Link;

class LinkCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Link::class);
    }

    public function rules(): array
    {
        return LinkValidation::getCreationRules();
    }
}
