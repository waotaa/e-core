<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NewsItemValidation;
use Vng\EvaCore\Models\NewsItem;

class NewsItemCreateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('create', NewsItem::class);
    }

    public function rules(): array
    {
        return NewsItemValidation::getCreationRules();
    }
}
