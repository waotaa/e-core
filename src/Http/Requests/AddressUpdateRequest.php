<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\AddressValidation;
use Vng\EvaCore\Models\Address;
use Vng\EvaCore\Repositories\AddressRepositoryInterface;

class AddressUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getAddress());
    }

    public function rules(): array
    {
        $address = $this->getAddress();
        if (!$address instanceof Address) {
            throw new \Exception('Cannot derive address from route');
        }
        return AddressValidation::make($this)->getUpdateRules($address);
    }

    protected function getAddress()
    {
        /** @var AddressRepositoryInterface $addressRepository */
        $addressRepository = App::make(AddressRepositoryInterface::class);
        return $addressRepository->find($this->route('addressId'));
    }
}
