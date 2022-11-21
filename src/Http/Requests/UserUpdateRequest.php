<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\UserValidation;
use Vng\EvaCore\Models\User;
use Vng\EvaCore\Repositories\UserRepositoryInterface;

class UserUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getUserModel());
    }

    public function rules(): array
    {
        $user = $this->getUserModel();
        if (!$user instanceof User) {
            throw new \Exception('Cannot derive user from route');
        }
        return UserValidation::make($this)->getUpdateRules($user);
    }

    protected function getUserModel()
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = App::make(UserRepositoryInterface::class);
        return $userRepository->find($this->route('userId'));
    }
}
