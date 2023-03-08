<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\LocalPartyValidation;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Repositories\LocalPartyRepositoryInterface;

class LocalPartyUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'localParty';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getLocalParty());
    }

    public function rules(): array
    {
        $localParty = $this->getLocalParty();
        if (!$localParty instanceof LocalParty) {
            throw new \Exception('Cannot derive localParty from route');
        }
        return LocalPartyValidation::make($this)->getUpdateRules($localParty);
    }

    protected function getLocalParty()
    {
        /** @var LocalPartyRepositoryInterface $localPartyRepository */
        $localPartyRepository = App::make(LocalPartyRepositoryInterface::class);
        return $localPartyRepository->find($this->getModelId());
    }
}
