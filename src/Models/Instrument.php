<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Builder;
use Vng\EvaCore\Casts\CleanedHtml;
use Vng\EvaCore\ElasticResources\InstrumentResource;
use Vng\EvaCore\Enums\DurationUnitEnum;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Interfaces\IsMemberInterface;
use Vng\EvaCore\Observers\InstrumentObserver;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\AreaService;
use Vng\EvaCore\Traits\CanSaveQuietly;
use Vng\EvaCore\Traits\HasContacts;
use Vng\EvaCore\Traits\HasOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Webpatser\Uuid\Uuid;

class Instrument extends SearchableModel
{
    use SoftDeletes, HasOwner, HasFactory, CanSaveQuietly, HasContacts, MutationLog;

    const REACH_LOCAL = 'local';
    const REACH_REGIONAL = 'regional';
    const REACH_NATIONAL = 'national';

    protected $table = 'instruments';
    protected string $elasticResource = InstrumentResource::class;
    protected $fillable = [
        'created_at',
        'updated_at',

        'uuid',
        'name',
        
        'is_active',
        'publish_from',
        'publish_to',

        // general information
        'aim',

        // format 2
        'summary',
        'method',
        'distinctive_approach',
        'target_group_description',
        'participation_conditions',
        'cooperation_partners',
        'additional_information',

        // practical information
        'location_description',
        'work_agreements',
        'application_instructions',
        'intensity_hours_per_week',
        'intensity_description',
        'total_duration_value',
        'total_duration_unit',
        'duration_description',
        'total_costs',
        'costs_description',

        // auxilary
        'import_mark',
    ];

    protected $dates = [
        'publish_from',
        'publish_to'
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected $casts = [
        'is_active' => 'boolean',
//        'short_description' => CleanedHtml::class,
//        'description' => CleanedHtml::class,
//        'conditions' => CleanedHtml::class,
        'aim' => CleanedHtml::class,
        'summary' => CleanedHtml::class,
        'method' => CleanedHtml::class,
        'distinctive_approach' => CleanedHtml::class,
        'target_group_description' => CleanedHtml::class,
        'participation_conditions' => CleanedHtml::class,
        'cooperation_partners' => CleanedHtml::class,
        'additional_information' => CleanedHtml::class,

        'location_description' => CleanedHtml::class,
        'work_agreements' => CleanedHtml::class,
        'costs_description' => CleanedHtml::class,
        'duration_description' => CleanedHtml::class,
        'intensity_description' => CleanedHtml::class,
        'application_instructions' => CleanedHtml::class,
    ];

    protected $with = [];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });

        self::deleted(function ($model) {
            $model->is_active = false;
        });

        static::observe(InstrumentObserver::class);
    }

    public function getSearchId()
    {
        return $this->uuid;
    }

    public function setTotalDurationUnitAttribute($value): void
    {
        if (is_null($value)) {
            $this->attributes['total_duration_unit'] = null;
            return;
        }
        $this->attributes['total_duration_unit'] = (new DurationUnitEnum($value))->getKey();
    }

    public function getTotalDurationUnitAttribute($value): ?string
    {
        if (is_null($value) || !in_array($value, DurationUnitEnum::keys())) {
            return null;
        }
        return DurationUnitEnum::$value();
    }

    public function getRawTotalDurationUnitAttribute(): ?string
    {
        return $this->attributes['total_duration_unit'] ?? null;
    }

    public function getTotalDurationHoursAttribute()
    {
        if (DurationUnitEnum::hour()->equals($this->total_duration_unit)) {
            return $this->total_duration_value;
        }

        $hoursPerWeek = $this->intensity_hours_per_week;
        if (DurationUnitEnum::day()->equals($this->total_duration_unit)) {
            $totalDurationWeeks = ceil($this->total_duration_value / 7);
            return $hoursPerWeek * $totalDurationWeeks;
        }
        if (DurationUnitEnum::week()->equals($this->total_duration_unit)) {
            return $hoursPerWeek * $this->total_duration_value;
        }
        if (DurationUnitEnum::month()->equals($this->total_duration_unit)) {
            $weeksPerMonth = 52/12;
            $totalDurationWeeks = $this->total_duration_value * $weeksPerMonth;
            return $hoursPerWeek * $totalDurationWeeks;
        }

        return null;
    }

    public function availableRegions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'available_region_instrument')
            ->withTimestamps()
            ->using(AvailableRegionInstrument::class);
    }

    public function availableTownships(): BelongsToMany
    {
        return $this->belongsToMany(Township::class, 'available_township_instrument')
            ->withTimestamps()
            ->using(AvailableTownshipInstrument::class);
    }

    public function availableNeighbourhoods(): BelongsToMany
    {
        return $this->belongsToMany(Neighbourhood::class, 'available_neighbourhood_instrument')
            ->withTimestamps()
            ->using(AvailableNeighbourhoodInstrument::class);
    }

    /**
     * Returns the exact ereas (no sub-areas) the instrument is available in when the availablity is
     * overridden throught the availableRegions, -Townships, or -Neighbourhoods properties
     *
     * @return Collection|null
     */
    public function getSpecifiedAvailableAreasAttribute(): ?Collection
    {
        $areas = collect([]);

        if ($this->availableRegions()->count() > 0) {
            $this->availableRegions()->each(function (Region $region) use ($areas) {
                $areas->add($region);
            });
        }

        if ($this->availableTownships()->count() > 0) {
            $this->availableTownships()->each(function (Township $township) use ($areas) {
                $areas->add($township);
                AreaService::removeAreaFromCollection($areas, $township->region);
            });
        }

        if ($this->availableNeighbourhoods()->count() > 0) {
            $this->availableNeighbourhoods()->each(function (Neighbourhood $neighbourhood) use ($areas) {
                $areas->add($neighbourhood);
                AreaService::removeAreaFromCollection($areas, $neighbourhood->township);
            });
        }

        return !$areas->isEmpty() ? $areas->values() : null;
    }

    /**
     * Returns the exact ereas (no sub-areas) the instrument is available in
     * either based on the specified availablity or based on the owner of the instrument
     *
     * @return Collection|null
     */
    public function getAvailableAreasAttribute(): Collection
    {
        if (!is_null($this->specifiedAvailableAreas)) {
            return $this->specifiedAvailableAreas;
        }

        if (is_null($this->organisation)) {
            return AreaService::getNationalAreas();
        }

        // Has owner: Return owner areas
        /** @var AreaInterface $organisationEntity */
        $organisationEntity = $this->organisation->organisationable;
        return $organisationEntity->getOwnAreas();
    }

    /**
     * Returns all (encompassing) areas the instrument is available in.
     * Includes all townships of an available region
     * Includes the region of an available township
     *
     * @return Collection
     */
    public function getAllAvailableAreasAttribute(): Collection
    {
        return AreaService::getEncompassingAreasForCollection($this->availableAreas);
    }

    /**
     * Checks if every region is available in the available areas
     *
     * @return bool
     */
    public function isNational(): bool
    {
        $nationalAreaIdentifiers = AreaService::getNationalAreas()->map(fn (AreaInterface $area) => $area->getAreaIdentifier());
        $availableAreaIdentifiers = $this->availableAreas->map(fn (AreaInterface $area) => $area->getAreaIdentifier());
        $regionsNotInAvailableAreas = $nationalAreaIdentifiers->diff($availableAreaIdentifiers);
        return $regionsNotInAvailableAreas->count() === 0;
    }

    public function isRegional(): bool
    {
        if ($this->isNational()) {
            return false;
        }
        $regionAreas = $this->availableAreas->filter(function (AreaInterface $area) {
            return $area->getType() === 'Region';
        });
        return $regionAreas->count() > 0;
    }

    public function isLocal(): bool
    {
        return !$this->isNational() && !$this->isRegional();
    }

    public function getReach()
    {
        if ($this->isLocal()) {
            return static::REACH_LOCAL;
        }
        if ($this->isRegional()) {
            return static::REACH_REGIONAL;
        }
        return static::REACH_NATIONAL;
    }

    public function parentInstrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function instrumentType(): BelongsTo
    {
        return $this->belongsTo(InstrumentType::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function registrationCodes(): HasMany
    {
        return $this->hasMany(RegistrationCode::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    // Property selection (singular choice)
    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }

    // Property lists (multiple choice)
    public function groupForms(): BelongsToMany
    {
        return $this->belongsToMany(GroupForm::class, 'group_form_instrument')
            ->withTimestamps()
            ->using(GroupFormInstrument::class);
    }

    public function locationTypes(): BelongsToMany
    {
        return $this->belongsToMany(LocationType::class, 'instrument_location_type')
            ->withTimestamps()
            ->using(InstrumentLocationType::class);
    }

    public function targetGroups(): BelongsToMany
    {
        return $this->belongsToMany(TargetGroup::class, 'instrument_target_group')
            ->withTimestamps()
            ->using(InstrumentTargetGroup::class);
    }

    public function tiles(): BelongsToMany
    {
        return $this->belongsToMany(Tile::class, 'instrument_tile')
            ->withTimestamps()
            ->using(InstrumentTile::class);
    }

    public function clientCharacteristics(): BelongsToMany
    {
        return $this->belongsToMany(ClientCharacteristic::class, 'client_characteristic_instrument')
            ->withTimestamps()
            ->using(ClientCharacteristicInstrument::class);
    }

    // Content
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    public function instrumentTrackers(): HasMany
    {
        return $this->hasMany(InstrumentTracker::class);
    }

    public function watchingUsers()
    {
        return $this->belongsToMany(Manager::class, 'instrument_trackers')->using(InstrumentTracker::class);
    }


    public function scopeOwnedBy(Builder $query, IsMemberInterface $user)
    {
        $instrumentRepository = $this->app->make(InstrumentRepositoryInterface::class);
        return $instrumentRepository->addMultipleOwnerConditions($query, $user->getAssociations());
    }
}
