<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\TileValidation;
use Vng\EvaCore\Models\RegistrationCode;

class TileUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('tile'));
    }

    public function rules(): array
    {
        $tile = $this->route('tile');
        if (!$tile instanceof RegistrationCode) {
            throw new \Exception('Cannot derive tile from route');
        }
        return TileValidation::make($this)->getUpdateRules($tile);
    }
}
