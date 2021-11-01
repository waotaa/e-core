<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\ElasticResources\NewsItemResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsItem extends SearchableModel
{
    use HasFactory;

    protected $table = 'news_items';

    protected string $elasticResource = NewsItemResource::class;

    protected $fillable = [
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

    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }
}
