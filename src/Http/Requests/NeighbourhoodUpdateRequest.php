<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NeighbourhoodValidation;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Repositories\NeighbourhoodRepositoryInterface;

class NeighbourhoodUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'neighbourhood';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getNeighbourhood());
    }

    public function rules(): array
    {
        $neighbourhood = $this->getNeighbourhood();
        if (!$neighbourhood instanceof Neighbourhood) {
            throw new \Exception('Cannot derive neighbourhood from route');
        }
        return NeighbourhoodValidation::make($this)->getUpdateRules($neighbourhood);
    }

    protected function getNeighbourhood()
    {
        /** @var NeighbourhoodRepositoryInterface $neighbourhoodRepository */
        $neighbourhoodRepository = App::make(NeighbourhoodRepositoryInterface::class);
        return $neighbourhoodRepository->find($this->getModelId());
    }
}
