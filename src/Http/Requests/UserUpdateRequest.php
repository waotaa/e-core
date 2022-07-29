<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\UserValidation;
use Vng\EvaCore\Models\User;

class UserUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('user'));
    }

    public function rules(): array
    {
        $user = $this->route('user');
        if (!$user instanceof User) {
            throw new \Exception('Cannot derive user from route');
        }
        return UserValidation::getUpdateRules($user);
    }
}
