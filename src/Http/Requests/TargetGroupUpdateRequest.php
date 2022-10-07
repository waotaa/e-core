<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\TargetGroupValidation;
use Vng\EvaCore\Models\TargetGroup;

class TargetGroupUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('targetGroup'));
    }

    public function rules(): array
    {
        $targetGroup = $this->route('targetGroup');
        if (!$targetGroup instanceof TargetGroup) {
            throw new \Exception('Cannot derive targetGroup from route');
        }
        return TargetGroupValidation::make($this)->getUpdateRules($targetGroup);
    }
}
