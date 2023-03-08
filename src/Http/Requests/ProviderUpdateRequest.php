<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ProviderValidation;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;

class ProviderUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'provider';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getProvider());
    }

    public function rules(): array
    {
        $provider = $this->getProvider();
        if (!$provider instanceof Provider) {
            throw new \Exception('Cannot derive provider from route');
        }
        return ProviderValidation::make($this)->getUpdateRules($provider);
    }

    protected function getProvider()
    {
        /** @var ProviderRepositoryInterface $providerRepository */
        $providerRepository = App::make(ProviderRepositoryInterface::class);
        return $providerRepository->find($this->getModelId());
    }
}
