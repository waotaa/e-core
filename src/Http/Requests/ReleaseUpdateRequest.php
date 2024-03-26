<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\ReleaseValidation;
use Vng\EvaCore\Models\Release;
use Vng\EvaCore\Repositories\RatingRepositoryInterface;
use Vng\EvaCore\Repositories\ReleaseRepositoryInterface;

class ReleaseUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'release';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getRelease());
    }

    public function rules(): array
    {
        $release = $this->getRelease();
        if (!$release instanceof Release) {
            throw new \Exception('Cannot derive release from route');
        }
        return ReleaseValidation::make($this)->getUpdateRules($release);
    }

    protected function getRelease()
    {
        /** @var ReleaseRepositoryInterface $releaseRepository */
        $releaseRepository = App::make(ReleaseRepositoryInterface::class);
        return $releaseRepository->find($this->getModelId());
    }
}
