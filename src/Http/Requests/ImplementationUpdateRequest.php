<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ImplementationValidation;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Repositories\ImplementationRepositoryInterface;

class ImplementationUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'implementation';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getImplementation());
    }

    public function rules(): array
    {
        $implementation = $this->getImplementation();
        if (!$implementation instanceof Implementation) {
            throw new \Exception('Cannot derive implementation from route');
        }
        return ImplementationValidation::make($this)->getUpdateRules($implementation);
    }

    protected function getImplementation()
    {
        /** @var ImplementationRepositoryInterface $implementationRepository */
        $implementationRepository = App::make(ImplementationRepositoryInterface::class);
        return $implementationRepository->find($this->getModelId());
    }
}
