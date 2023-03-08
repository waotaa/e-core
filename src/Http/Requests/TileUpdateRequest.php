<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\TileValidation;
use Vng\EvaCore\Models\RegistrationCode;
use Vng\EvaCore\Repositories\TileRepositoryInterface;

class TileUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'tile';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getTile());
    }

    public function rules(): array
    {
        $tile = $this->getTile();
        if (!$tile instanceof RegistrationCode) {
            throw new \Exception('Cannot derive tile from route');
        }
        return TileValidation::make($this)->getUpdateRules($tile);
    }

    protected function getTile()
    {
        /** @var TileRepositoryInterface $tileRepository */
        $tileRepository = App::make(TileRepositoryInterface::class);
        return $tileRepository->find($this->getModelId());
    }
}
