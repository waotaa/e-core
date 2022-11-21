<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\RegistrationCodeValidation;
use Vng\EvaCore\Models\RegistrationCode;
use Vng\EvaCore\Repositories\RegistrationCodeRepositoryInterface;

class RegistrationCodeUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getRegistrationCode());
    }

    public function rules(): array
    {
        $registrationCode = $this->getRegistrationCode();
        if (!$registrationCode instanceof RegistrationCode) {
            throw new \Exception('Cannot derive registrationCode from route');
        }
        return RegistrationCodeValidation::make($this)->getUpdateRules($registrationCode);
    }

    protected function getRegistrationCode()
    {
        /** @var RegistrationCodeRepositoryInterface $registrationCodeRepository */
        $registrationCodeRepository = App::make(RegistrationCodeRepositoryInterface::class);
        return $registrationCodeRepository->find($this->route('registrationCodeId'));
    }
}
