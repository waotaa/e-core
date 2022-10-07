<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NeighbourhoodValidation;
use Vng\EvaCore\Models\Neighbourhood;

class NeighbourhoodUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('neighbourhood'));
    }

    public function rules(): array
    {
        $neighbourhood = $this->route('neighbourhood');
        if (!$neighbourhood instanceof Neighbourhood) {
            throw new \Exception('Cannot derive neighbourhood from route');
        }
        return NeighbourhoodValidation::make($this)->getUpdateRules($neighbourhood);
    }
}
