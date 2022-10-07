<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\RatingValidation;
use Vng\EvaCore\Models\Rating;

class RatingUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('rating'));
    }

    public function rules(): array
    {
        $rating = $this->route('rating');
        if (!$rating instanceof Rating) {
            throw new \Exception('Cannot derive rating from route');
        }
        return RatingValidation::make($this)->getUpdateRules($rating);
    }
}
