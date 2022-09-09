<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ClientCharacteristicDetachInstrumentRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('detachInstrument', $this->route('clientCharacteristic'));
    }

    public function rules(): array
    {
        return [
            'instrument_id' => [
                'required'
            ]
        ];
    }
}
