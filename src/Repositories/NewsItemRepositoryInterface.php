<?php

namespace Vng\EvaCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Vng\EvaCore\Http\Requests\NewsItemCreateRequest;
use Vng\EvaCore\Http\Requests\NewsItemUpdateRequest;
use Vng\EvaCore\Models\NewsItem;

interface NewsItemRepositoryInterface extends BaseRepositoryInterface
{
    public function create(NewsItemCreateRequest $request): NewsItem;
    public function update(NewsItem $newsItem, NewsItemUpdateRequest $request): NewsItem;

    public function getQueryOwnedByEnvironments(Collection $environments): Builder;
    public function addRelatedEnvironmentsConditions(Builder $query, Collection $environments);
}
