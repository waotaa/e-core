<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Vng\EvaCore\ElasticResources\EnvironmentResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vng\EvaCore\Traits\HasPermanentSlug;

class Environment extends SearchableModel
{
    use HasFactory, SoftDeletes, HasPermanentSlug;

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

    public function featuredOrganisations(): BelongsToMany
    {
        return $this->belongsToMany(Organisation::class, 'featured_organisations');
    }
}
