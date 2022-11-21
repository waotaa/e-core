<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\OrganisationValidation;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;

class OrganisationUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getOrganisation());
    }

    public function rules(): array
    {
        $organisation = $this->getOrganisation();
        if (!$organisation instanceof Organisation) {
            throw new \Exception('Cannot derive organisation from route');
        }
        return OrganisationValidation::make($this)->getUpdateRules($organisation);
    }

    protected function getOrganisation()
    {
        /** @var OrganisationRepositoryInterface $organisationRepository */
        $organisationRepository = App::make(OrganisationRepositoryInterface::class);
        return $organisationRepository->find($this->route('organisationId'));
    }
}
