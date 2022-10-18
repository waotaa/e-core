<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Vng\EvaCore\Traits\HasContacts;

class Organisation extends Model
{
    use HasFactory, SoftDeletes, HasContacts;

    protected $table = 'organisations';

    protected $fillable = [];

    public function getIdentifierAttribute()
    {
        return $this->name . ' - ' . __($this->type);
    }

    public function getNameAttribute()
    {
        return $this->organisationable?->name;
    }

    public function getSlugAttribute()
    {
        return $this->organisationable?->slug;
    }

    public function getTypeAttribute()
    {
        return $this->organisationable?->type;
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(Manager::class);
    }

    public function hasMember(Model $user): bool
    {
        return $this->managers && $this->managers->contains($user->id);
    }

    public function scopeIsMember(Builder $query, Manager $manager): Builder
    {
        return $query->whereIn('id', $manager->organisations->pluck('id'));
    }


    public function localParty(): HasOne
    {
        return $this->hasOne(LocalParty::class, 'organisation_id');
    }

    public function regionalParty(): HasOne
    {
        return $this->hasOne(RegionalParty::class, 'organisation_id');
    }

    public function nationalParty(): HasOne
    {
        return $this->hasOne(NationalParty::class, 'organisation_id');
    }

    public function partnership(): HasOne
    {
        return $this->hasOne(Partnership::class, 'organisation_id');
    }

    /**
     * Optional relation to make it easier to find the matching Organisation entity
     * @return MorphTo
     */
    public function organisationable(): MorphTo
    {
        return $this->morphTo();
    }

    public function featuringEnvironments():  BelongsToMany
    {
        return $this->belongsToMany(Environment::class, 'featured_organisations');
    }

    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class);
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }

    public function ownsInstrument(Instrument $instrument): bool
    {
        return $this->instruments && $this->instruments->contains($instrument->id);
    }
}
