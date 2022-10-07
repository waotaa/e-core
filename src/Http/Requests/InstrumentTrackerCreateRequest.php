<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\InstrumentTrackerValidation;
use Vng\EvaCore\Models\InstrumentTracker;

class InstrumentTrackerCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', InstrumentTracker::class);
    }

    public function rules(): array
    {
        return InstrumentTrackerValidation::getCreationRules();
    }
}
