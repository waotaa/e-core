<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ProfessionalValidation;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;

class ProfessionalUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'professional';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getProfessional());
    }

    public function rules(): array
    {
        $professional = $this->getProfessional();
        if (!$professional instanceof Professional) {
            throw new \Exception('Cannot derive professional from route');
        }
        return ProfessionalValidation::make($this)->getUpdateRules($professional);
    }

    protected function getProfessional()
    {
        /** @var ProfessionalRepositoryInterface $professionalRepository */
        $professionalRepository = App::make(ProfessionalRepositoryInterface::class);
        return $professionalRepository->find($this->getModelId());
    }
}
