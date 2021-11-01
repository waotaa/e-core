<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\ElasticResources\EnvironmentResource;
use Vng\EvaCore\Traits\HasMembers;
use Vng\EvaCore\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Environment extends SearchableModel
{
    use HasFactory, SoftDeletes, HasSlug, HasMembers;

    protected string $elasticResource = EnvironmentResource::class;

    protected $fillable = [
        'name',
        'slug',
        'description_header',
        'description',
        'logo',
        'color_primary',
        'color_secondairy',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function featuredAssociation(): MorphTo
    {
        return $this->morphTo();
    }

    public function newsItems(): HasMany
    {
        return $this->hasMany(NewsItem::class);
    }
}
