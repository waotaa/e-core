<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ReleaseChangeValidation;
use Vng\EvaCore\Models\ReleaseChange;
use Vng\EvaCore\Repositories\ReleaseChangeRepositoryInterface;

class ReleaseChangeUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'releaseChange';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getReleaseChange());
    }

    public function rules(): array
    {
        $releaseChange = $this->getReleaseChange();
        if (!$releaseChange instanceof ReleaseChange) {
            throw new \Exception('Cannot derive releaseChange from route');
        }
        return ReleaseChangeValidation::make($this)->getUpdateRules($releaseChange);
    }

    protected function getReleaseChange(): ?Model
    {
        /** @var ReleaseChangeRepositoryInterface $releaseChangeRepository */
        $releaseChangeRepository = App::make(ReleaseChangeRepositoryInterface::class);
        return $releaseChangeRepository->find($this->getModelId());
    }
}
