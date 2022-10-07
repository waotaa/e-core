<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\NewsItemCreateRequest;
use Vng\EvaCore\Http\Requests\NewsItemUpdateRequest;
use Vng\EvaCore\Models\NewsItem;

interface NewsItemRepositoryInterface extends BaseRepositoryInterface
{
    public function create(NewsItemCreateRequest $request): NewsItem;
    public function update(NewsItem $newsItem, NewsItemUpdateRequest $request): NewsItem;
}
