<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ClientCharacteristicValidation;
use Vng\EvaCore\Models\ClientCharacteristic;

class ClientCharacteristicUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('clientCharacteristic'));
    }

    public function rules(): array
    {
        $clientCharacteristic = $this->route('clientCharacteristic');
        if (!$clientCharacteristic instanceof ClientCharacteristic) {
            throw new \Exception('Cannot derive clientCharacteristic from route');
        }
        return ClientCharacteristicValidation::getUpdateRules($clientCharacteristic);
    }
}
