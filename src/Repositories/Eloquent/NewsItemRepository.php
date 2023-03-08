<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Vng\EvaCore\Http\Requests\NewsItemCreateRequest;
use Vng\EvaCore\Http\Requests\NewsItemUpdateRequest;
use Vng\EvaCore\Models\NewsItem;
use Vng\EvaCore\Repositories\NewsItemRepositoryInterface;

class NewsItemRepository extends BaseRepository implements NewsItemRepositoryInterface
{
    public string $model = NewsItem::class;

    public function create(NewsItemCreateRequest $request): NewsItem
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(NewsItem $newsItem, NewsItemUpdateRequest $request): NewsItem
    {
        return $this->saveFromRequest($newsItem, $request);
    }

    public function saveFromRequest(NewsItem $newsItem, FormRequest $request): NewsItem
    {
        $newsItem->fill([
            'title' => $request->input('title'),
            'sub_title' => $request->input('sub_title'),
            'body' => $request->input('body'),
            'teaser' => $request->input('teaser'),
            'publish_from' => $request->input('publish_from'),
            'publish_to' => $request->input('publish_to'),
        ]);

        $newsItem->environment()->associate($request->input('environment_id'));

        $newsItem->save();
        return $newsItem;
    }

    public function addRelatedEnvironmentsConditions(Builder $query, Collection $environments)
    {
        return $query->whereIn('environment_id', $environments->pluck('id'));
    }

    public function getQueryOwnedByEnvironments(Collection $environments): Builder
    {
        return $this->addRelatedEnvironmentsConditions($this->builder(), $environments);
    }
}
