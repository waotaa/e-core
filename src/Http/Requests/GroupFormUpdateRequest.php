<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\GroupFormValidation;
use Vng\EvaCore\Models\GroupForm;

class GroupFormUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('groupForm'));
    }

    public function rules(): array
    {
        $groupForm = $this->route('groupForm');
        if (!$groupForm instanceof GroupForm) {
            throw new \Exception('Cannot derive groupForm from route');
        }
        return GroupFormValidation::make($this)->getUpdateRules($groupForm);
    }
}
