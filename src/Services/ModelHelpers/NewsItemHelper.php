<?php


namespace Vng\EvaCore\Services\ModelHelpers;

use Vng\EvaCore\Models\NewsItem;
use DateTime;
use Illuminate\Database\Eloquent\Builder;

class NewsItemHelper
{
    private NewsItem $newsItem;

    public function __construct(NewsItem $newsItem)
    {
        $this->newsItem = $newsItem;
    }

    public static function create(NewsItem $newsItem): NewsItemHelper
    {
        return new static($newsItem);
    }

    public function isPublished(): bool
    {
        $today = (new DateTime())->setTime(0, 0);
        $publishFrom = $this->newsItem->getAttribute('publish_from');
        if (!is_null($publishFrom) && $publishFrom > $today) {
            return false;
        }

        $publishTo = $this->newsItem->getAttribute('publish_to');
        if (!is_null($publishTo) && $publishTo < $today) {
            return false;
        }
        return true;
    }

    public static function queryPublished(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            $today = (new DateTime())->setTime(0, 0);
            $builder
                ->where(function (Builder $builder) use ($today) {
                    return $builder
                        ->whereNull('publish_from')
                        ->orWhereDate('publish_from', '<=', $today);
                })
                ->where(function (Builder $builder) use ($today) {
                    return $builder
                        ->whereNull('publish_to')
                        ->orWhereDate('publish_to', '>', $today);
                });
        });
    }

    public static function queryUnpublished(Builder $builder): Builder
    {
        return $builder->where(function (Builder $builder) {
            $today = (new DateTime())->setTime(0, 0);
            $builder
                ->orWhere(function (Builder $builder) use ($today) {
                    return $builder
                        ->whereNotNull('publish_from')
                        ->whereDate('publish_from', '>', $today);
                })
                ->orWhere(function (Builder $builder) use ($today) {
                    return $builder
                        ->whereNotNull('publish_to')
                        ->whereDate('publish_to', '<', $today);
                });
        });
    }
}
