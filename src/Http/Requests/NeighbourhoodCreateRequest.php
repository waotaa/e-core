<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NeighbourhoodValidation;
use Vng\EvaCore\Models\Neighbourhood;

class NeighbourhoodCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Neighbourhood::class);
    }

    public function rules(): array
    {
        return NeighbourhoodValidation::getCreationRules();
    }
}
