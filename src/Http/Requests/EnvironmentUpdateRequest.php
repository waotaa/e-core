<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\EnvironmentValidation;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;

class EnvironmentUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'environment';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getEnvironment());
    }

    public function rules(): array
    {
        $environment = $this->getEnvironment();
        if (!$environment instanceof Environment) {
            throw new \Exception('Cannot derive environment from route');
        }
        return EnvironmentValidation::make($this)->getUpdateRules($environment);
    }

    protected function getEnvironment()
    {
        /** @var EnvironmentRepositoryInterface $environmentRepository */
        $environmentRepository = App::make(EnvironmentRepositoryInterface::class);
        return $environmentRepository->find($this->getModelId());
    }
}
