<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Interfaces\OrganisationEntityInterface;
use Vng\EvaCore\Observers\OrganisationObserver;
use Vng\EvaCore\Traits\HasContacts;

class Organisation extends Model
{
    use HasFactory, SoftDeletes, HasContacts;

    public bool $isCascadingDelete = false;
    public bool $isCascadingRestore = false;

    protected $table = 'organisations';

    protected $fillable = [
        'organisation_type'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(OrganisationObserver::class);
    }

    public function getIdentifierAttribute()
    {
        return $this->name . ' - ' . __($this->type);
    }

    public function getShortIdentifierAttribute()
    {
        return $this->id . '-' . $this->slug;
    }

    // Organisation type / variant

    public function setOrganisationType(Model $model): void
    {
        // Zorg dat het model het juiste interface implementeert
        if (!($model instanceof OrganisationEntityInterface)) {
            throw new InvalidArgumentException('The provided model must implement OrganisationEntityInterface.');
        }

        // Stel organisation_type in op de classname van het model
        $this->organisation_type = class_basename($model);
    }

    public function getOrganisationVariantAttribute()
    {
        if ($this->organisation_type) {
            $relationName = \Illuminate\Support\Str::camel($this->organisation_type);

            // Controleer met `withTrashed()` of de relatie bestaat
            if (method_exists($this, $relationName)) {
                return $this->{$relationName}()->withTrashed()->first();
            }
        }

        // Controleer de relaties op volgorde, inclusief soft-deleted records
        if ($this->localParty()->withTrashed()->exists()) {
            return $this->localParty()->withTrashed()->first();
        }

        if ($this->regionalParty()->withTrashed()->exists()) {
            return $this->regionalParty()->withTrashed()->first();
        }

        if ($this->nationalParty()->withTrashed()->exists()) {
            return $this->nationalParty()->withTrashed()->first();
        }

        if ($this->partnership()->withTrashed()->exists()) {
            return $this->partnership()->withTrashed()->first();
        }

        // Werp een exception als geen variant gevonden wordt
        throw new RuntimeException("Organisation with ID {$this->id} has no valid organisation variant.");
    }


    public function getNameAttribute()
    {
        return $this->organisation_variant?->name;
    }

    public function getSlugAttribute()
    {
        return $this->organisation_variant?->slug;
    }

    public function getTypeAttribute()
    {
        return $this->organisation_variant?->type;
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

    public function scopeNationalParty($query)
    {
        return $query->whereHas('nationalParty');
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
        $nationalOrganisations = Organisation::query()
            ->whereHas('nationalParty')
            ->get();
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
        /** @var AreaInterface $organisationVariant */
        $organisationVariant = $this->getOrganisationVariantAttribute();
        if (is_null($organisationVariant)) {
            Log::warning('Organisation without organisationVariant encountered - org id ['. $this->getAttribute('id').']');
            return collect();
        }
        return $organisationVariant->getEncompassingAreas();
    }
}
