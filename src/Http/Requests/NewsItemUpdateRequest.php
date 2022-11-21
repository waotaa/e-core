<?php

namespace Vng\EvaCore\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Vng\EvaCore\Http\Validation\NewsItemValidation;
use Vng\EvaCore\Models\NewsItem;
use Vng\EvaCore\Repositories\NewsItemRepositoryInterface;

class NewsItemUpdateRequest extends FormRequest implements FormRequestInterface
{
    public function authorize(): bool
    {
        return Auth::user()->can('update', $this->getNewsItem());
    }

    public function rules(): array
    {
        $newsItem = $this->getNewsItem();
        if (!$newsItem instanceof NewsItem) {
            throw new \Exception('Cannot derive newsItem from route');
        }
        return NewsItemValidation::make($this)->getUpdateRules($newsItem);
    }

    protected function getNewsItem()
    {
        /** @var NewsItemRepositoryInterface $newsItemRepository */
        $newsItemRepository = App::make(NewsItemRepositoryInterface::class);
        return $newsItemRepository->find($this->route('newsItemId'));
    }
}
