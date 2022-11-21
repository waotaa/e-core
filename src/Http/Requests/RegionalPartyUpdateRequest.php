<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\RegionalPartyValidation;
use Vng\EvaCore\Models\RegionalParty;
use Vng\EvaCore\Repositories\RegionalPartyRepositoryInterface;

class RegionalPartyUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getRegionalParty());
    }

    public function rules(): array
    {
        $regionalParty = $this->getRegionalParty();
        if (!$regionalParty instanceof RegionalParty) {
            throw new \Exception('Cannot derive regionalParty from route');
        }
        return RegionalPartyValidation::make($this)->getUpdateRules($regionalParty);
    }

    protected function getRegionalParty()
    {
        /** @var RegionalPartyRepositoryInterface $regionalPartyRepository */
        $regionalPartyRepository = App::make(RegionalPartyRepositoryInterface::class);
        return $regionalPartyRepository->find($this->route('regionalPartyId'));
    }
}
