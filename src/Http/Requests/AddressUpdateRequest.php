<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\AddressValidation;
use Vng\EvaCore\Models\Address;
use Vng\EvaCore\Repositories\AddressRepositoryInterface;

class AddressUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'address';

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
        return $addressRepository->find($this->getModelId());
    }
}
