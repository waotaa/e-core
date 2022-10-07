<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ManagerValidation;

class ManagerUpdateRequest extends FormRequest implements FormRequestInterface
{
//    public function authorize(): bool
//    {
//        return Auth::user()->can('update', $this->route('manager'));
//    }

    public function rules(): array
    {
        return ManagerValidation::getCreationRules();
    }
}
