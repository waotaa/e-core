<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NationalPartyValidation;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Repositories\NationalPartyRepositoryInterface;

class NationalPartyUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getNationalParty());
    }

    public function rules(): array
    {
        $nationalParty = $this->getNationalParty();
        if (!$nationalParty instanceof NationalParty) {
            throw new \Exception('Cannot derive nationalParty from route');
        }
        return NationalPartyValidation::make($this)->getUpdateRules($nationalParty);
    }

    protected function getNationalParty()
    {
        /** @var NationalPartyRepositoryInterface $nationalPartyRepository */
        $nationalPartyRepository = App::make(NationalPartyRepositoryInterface::class);
        return $nationalPartyRepository->find($this->route('nationalPartyId'));
    }
}
