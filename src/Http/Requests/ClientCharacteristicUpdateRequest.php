<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ClientCharacteristicValidation;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Repositories\ClientCharacteristicRepositoryInterface;

class ClientCharacteristicUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'clientCharacteristic';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getClientCharacteristic());
    }

    public function rules(): array
    {
        $clientCharacteristic = $this->getClientCharacteristic();
        if (!$clientCharacteristic instanceof ClientCharacteristic) {
            throw new \Exception('Cannot derive clientCharacteristic from route');
        }
        return ClientCharacteristicValidation::make($this)->getUpdateRules($clientCharacteristic);
    }

    protected function getClientCharacteristic()
    {
        /** @var ClientCharacteristicRepositoryInterface $clientCharacteristicsRepository */
        $clientCharacteristicsRepository = App::make(ClientCharacteristicRepositoryInterface::class);
        return $clientCharacteristicsRepository->find($this->getModelId());
    }
}
