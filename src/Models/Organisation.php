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
use Illuminate\Support\Collection;
use Vng\EvaCore\Interfaces\AreaInterface;
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

    public function getShortIdentifierAttribute()
    {
        return $this->id . '-' . $this->slug;
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

    public function hasMember(Manager $manager): bool
    {
        return $this->managers && $this->managers->contains($manager->id);
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
        return $this->belongsToMany(Environment::class, 'featured_organisations')->using(FeaturedOrganisation::class);
    }

    public function ownedAddresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function ownedContacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function ownedEnvironments(): HasMany
    {
        return $this->hasMany(Environment::class);
    }

    public function ownedInstruments(): HasMany
    {
        return $this->instruments();
    }

    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class);
    }

    public function ownedProviders(): HasMany
    {
        return $this->providers();
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }

    public function ownsInstrument(Instrument $instrument): bool
    {
        return $this->instruments && $this->instruments->contains($instrument->id);
    }

    public function getOverarchingOrganisations(): Collection
    {
        // include this organisation
        $organisations = collect([$this]);

        if (!is_null($this->localParty)){
            /** @var LocalParty $localParty */
            $localParty = $this->localParty;
            // add overarching regional party organisations
            $regionalParties = $localParty->township->region->regionalParties;
            $regionalParties->each(fn (RegionalParty $rp) => $organisations->add($rp->organisation));

            // add overarching partnership organisations
            $partnerships = $localParty->township->partnerships;
            $partnerships->each(fn (Partnership $p) => $organisations->add($p->organisation));
        }

        // we include all national parties
        $nationalOrganisations = Organisation::query()->whereHasMorph('organisationable', [NationalParty::class])->get();
        $organisations = $organisations->merge($nationalOrganisations);

        return $organisations;

        // Query only attempt
//        $q = Organisation::query()->whereHasMorph('organisationable', [NationalParty::class]);
//        if (!is_null($this->localParty)) {
//            $q->orWhereHasMorph('organisationable', [RegionalParty::class], function (Builder $query) {
//                $query->whereHas('region', function (Builder $query) {
//                    $query->whereHas('townships', function (Builder $query) {
//                        $query->whereHas('localParties', function (Builder $query) {
//                            $query->where('id', $this->localParty);
//                        });
//                    });
//                });
//            });
//        }
    }

    /**
     * Get all areas this organisation operates in (if not a national party)
     * @return Collection
     */
    public function getAreasActiveInAttribute()
    {
        if (!is_null($this->nationalParty()->first())) {
            return collect();
        }
        /** @var AreaInterface $organisationEntity */
        $organisationEntity = $this->organisationable()->first();
        return $organisationEntity->getEncompassingAreas();
    }
}
