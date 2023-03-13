<?php

namespace Vng\EvaCore\Models;

use Database\Factories\NewsItemFactory;
use Vng\EvaCore\ElasticResources\NewsItemResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Observers\NewsItemObserver;

class NewsItem extends SearchableModel
{
    use HasFactory;

    protected $table = 'news_items';

    protected string $elasticResource = NewsItemResource::class;

    protected $fillable = [
        'created_at',
        'updated_at',

        'title',
        'sub_title',
        'body',
        'teaser',
        'publish_from',
        'publish_to',
    ];

    protected $dates = [
        'publish_from',
        'publish_to'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(NewsItemObserver::class);
    }

    protected static function newFactory()
    {
        return NewsItemFactory::new();
    }

    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }
}
