<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\TargetGroupValidation;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Repositories\TargetGroupRepositoryInterface;

class TargetGroupUpdateRequest extends BaseFormRequest implements FormRequestInterface
{
    protected $modelName = 'targetGroup';

    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getTargetGroup());
    }

    public function rules(): array
    {
        $targetGroup = $this->getTargetGroup();
        if (!$targetGroup instanceof TargetGroup) {
            throw new \Exception('Cannot derive targetGroup from route');
        }
        return TargetGroupValidation::make($this)->getUpdateRules($targetGroup);
    }

    protected function getTargetGroup()
    {
        /** @var TargetGroupRepositoryInterface $targetGroupRepository */
        $targetGroupRepository = App::make(TargetGroupRepositoryInterface::class);
        return $targetGroupRepository->find($this->getModelId());
    }
}
