<?php

namespace Vng\EvaCore\Models;

use Database\Factories\EnvironmentFactory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Str;
use Vng\EvaCore\ElasticResources\EnvironmentResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vng\EvaCore\Observers\EnvironmentObserver;
use Vng\EvaCore\Services\AreaService;
use Vng\EvaCore\Traits\HasOwner;
use Vng\EvaCore\Traits\HasPermanentSlug;

class Environment extends SearchableModel
{
    use HasFactory, HasOwner, SoftDeletes, HasPermanentSlug;

    protected string $elasticResource = EnvironmentResource::class;

    protected $fillable = [
        'created_at',
        'updated_at',

        'name',
        'slug',
        'description_header',
        'description',
        'logo',
        'color_primary',
        'color_secondary',
        'user_pool_id',
        'user_pool_client_id',
        'url',

        'dashboard_username',
        'dashboard_password'
    ];

    protected $hidden = [
        'dashboard_password'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(EnvironmentObserver::class);
    }

    protected static function newFactory()
    {
        return EnvironmentFactory::new();
    }

    public function setDashboardPasswordAttribute($value)
    {
        $this->attributes['dashboard_password'] = app(Encrypter::class)->encrypt($value);
    }

    public function getDashboardPasswordAttribute()
    {
        try {
            return app(Encrypter::class)->decrypt($this->attributes['dashboard_password']);
        } catch (DecryptException $e) {
            // Handle decryption errors (e.g., invalid encryption or wrong key)
            return null;
        }
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
        return Str::slug($this->name);
    }

    public function getFeaturedAreasAttribute()
    {
        $organisations = $this->featuredOrganisations()->get();
        $areas = $organisations->map(function(Organisation $organisation) {
            return $organisation->getAreasActiveInAttribute();
        })
            ->filter()
            ->flatten();
        return AreaService::removeDuplicateAreas($areas);
    }
}
