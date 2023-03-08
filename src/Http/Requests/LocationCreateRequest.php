<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\LocationValidation;
use Vng\EvaCore\Models\Location;

class LocationCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Location::class);
    }

    public function rules(): array
    {
        return LocationValidation::make($this)->getCreationRules();
    }
}
