<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NewsItemValidation;
use Vng\EvaCore\Models\NewsItem;

class NewsItemUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->route('newsItem'));
    }

    public function rules(): array
    {
        $newsItem = $this->route('newsItem');
        if (!$newsItem instanceof NewsItem) {
            throw new \Exception('Cannot derive newsItem from route');
        }
        return NewsItemValidation::make($this)->getUpdateRules($newsItem);
    }
}
