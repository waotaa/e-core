<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\TargetGroupValidation;
use Vng\EvaCore\Models\TargetGroup;

class TargetGroupCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', TargetGroup::class);
    }

    public function rules(): array
    {
        return TargetGroupValidation::getCreationRules();
    }
}
