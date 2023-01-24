<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\NewsItem;

class NewsItemFromArrayService extends BaseFromArrayService
{
    public function handle(): ?Model
    {
        $data = $this->data;

        $environment = Environment::query()->where('slug', $data['environment_slug'])->firstOrFail();

        /** @var NewsItem $newsItem */
        $newsItem = NewsItem::query()->firstOrNew([
            'title' => $data['title'],
            'environment_id' => $environment->id,
        ]);
        $newsItem = $newsItem->fill($data);

        $newsItem->environment()->associate($environment);
        $newsItem->saveQuietly();
        return $newsItem;
    }
}