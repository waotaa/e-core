<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ReleaseChangeValidation;
use Vng\EvaCore\Models\Release;
use Vng\EvaCore\Repositories\ReleaseRepositoryInterface;

class ReleaseChangeUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'releaseChange';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getReleaseChange());
    }

    public function rules(): array
    {
        $release = $this->getReleaseChange();
        if (!$release instanceof Release) {
            throw new \Exception('Cannot derive release from route');
        }
        return ReleaseChangeValidation::make($this)->getUpdateRules($release);
    }

    protected function getReleaseChange(): ?Model
    {
        /** @var ReleaseRepositoryInterface $releaseRepository */
        $releaseRepository = App::make(ReleaseRepositoryInterface::class);
        return $releaseRepository->find($this->getModelId());
    }
}
