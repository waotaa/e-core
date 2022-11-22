<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Vng\EvaCore\ElasticResources\EnvironmentResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vng\EvaCore\Observers\EnvironmentObserver;
use Vng\EvaCore\Traits\HasOwner;
use Vng\EvaCore\Traits\HasPermanentSlug;

class Environment extends SearchableModel
{
    use HasFactory, HasOwner, SoftDeletes, HasPermanentSlug;

    protected string $elasticResource = EnvironmentResource::class;

    protected $fillable = [
        'name',
        'slug',
        'description_header',
        'description',
        'logo',
        'color_primary',
        'color_secondary',
        'user_pool_id',
        'user_pool_client_id',
        'url'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(EnvironmentObserver::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function featuredAssociation(): MorphTo
    {
        return $this->morphTo();
    }

    public function professionals(): HasMany
    {
        return $this->hasMany(Professional::class);
    }

    public function newsItems(): HasMany
    {
        return $this->hasMany(NewsItem::class);
    }

    public function featuredOrganisations(): BelongsToMany
    {
        return $this->belongsToMany(Organisation::class, 'featured_organisations')->using(FeaturedOrganisation::class);
    }

    public function deriveUserPoolName()
    {
        // heel misschien created_by erin verwerken om zeker te zijn van uniciteit
        return $this->slug;
    }
}
