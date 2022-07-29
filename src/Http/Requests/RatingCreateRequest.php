<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\RatingValidation;
use Vng\EvaCore\Models\Rating;

class RatingCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', Rating::class);
    }

    public function rules(): array
    {
        return RatingValidation::getCreationRules();
    }
}
