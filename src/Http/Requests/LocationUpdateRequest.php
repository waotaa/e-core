<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\LocationValidation;
use Vng\EvaCore\Models\Provider;

class LocationUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('location'));
    }

    public function rules(): array
    {
        $location = $this->route('location');
        if (!$location instanceof Provider) {
            throw new \Exception('Cannot derive location from route');
        }
        return LocationValidation::getUpdateRules($location);
    }
}
