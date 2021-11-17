<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Casts\CleanedHtml;
use Vng\EvaCore\ElasticResources\InstrumentResource;
use Vng\EvaCore\Enums\CostsUnitEnum;
use Vng\EvaCore\Enums\DurationUnitEnum;
use Vng\EvaCore\Enums\LocationEnum;
use Vng\EvaCore\Observers\InstrumentObserver;
use Vng\EvaCore\Traits\CanSaveQuietly;
use Vng\EvaCore\Traits\HasContacts;
use Vng\EvaCore\Traits\HasOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Webpatser\Uuid\Uuid;

class Instrument extends SearchableModel
{
    use SoftDeletes, HasOwner, HasFactory, CanSaveQuietly, HasContacts;

    protected $table = 'instruments';
    protected string $elasticResource = InstrumentResource::class;
    public function getSearchId()
    {
        return $this->uuid;
    }

    protected $fillable = [
        'uuid',
        'name',
        'is_active',
        'publish_from',
        'publish_to',
        'is_nationally_available',

        // descriptions
        'short_description',
        'description',
        'application_instructions',
        'conditions',
        'distinctive_approach',
        'cooperation_partners',

        // right sidebar
        'aim',

        // info section
        'costs',
        'costs_unit',
        'duration',
        'duration_unit',
        'location',
        'location_description',

        // format 2
        'summary',
        'method',
        'target_group_description',
        'participation_conditions',
        'additional_information',
        'location_description',
        'work_agreements',
        'intenstiy_hours_per_week',
        'total_duration_value',
        'total_duration_unit',
        'total_costs',
        'intensity_duration_costs_description',

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
        'is_nationally_available' => 'boolean',
        'short_description' => CleanedHtml::class,
        'description' => CleanedHtml::class,
        'application_instructions' => CleanedHtml::class,
        'conditions' => CleanedHtml::class,
        'distinctive_approach' => CleanedHtml::class,
        'cooperation_partners' => CleanedHtml::class,
        'aim' => CleanedHtml::class,

        'summary' => CleanedHtml::class,
        'method' => CleanedHtml::class,
        'target_group_description' => CleanedHtml::class,
        'participation_conditions' => CleanedHtml::class,
        'additional_information' => CleanedHtml::class,
        'location_description' => CleanedHtml::class,
        'work_agreements' => CleanedHtml::class,
        'intensity_duration_costs_description' => CleanedHtml::class,
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

    public function getCostsUnitAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        if (in_array($value, CostsUnitEnum::keys())) {
            return CostsUnitEnum::$value();
        }
        return $this->attributes['costs_unit'];
    }

    public function getRawCostsUnitAttribute()
    {
        return $this->attributes['costs_unit'];
    }

    public function getTotalDurationUnitAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        if (in_array($value, DurationUnitEnum::keys())) {
            return DurationUnitEnum::$value();
        }
        return $this->attributes['total_duration_unit'];
    }

    public function getRawTotalDurationUnitAttribute()
    {
        return $this->attributes['total_duration_unit'];
    }

    public function getLocationAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }
        if (in_array($value, LocationEnum::keys())) {
            return LocationEnum::$value();
        }
        return $this->attributes['location'];
    }

    public function getRawLocationAttribute()
    {
        return $this->attributes['location'];
    }

    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'area_instrument')
            ->withTimestamps()
            ->using(AreaInstrument::class);
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

    public function availableTownshipParts(): BelongsToMany
    {
        return $this->belongsToMany(TownshipPart::class, 'available_township_part_instrument')
            ->withTimestamps()
            ->using(AvailableTownshipPartInstrument::class);
    }

    /**
     * Collects all areas where this instruments areas are located in
     * @return Collection
     */
    public function getAreasLocatedInAttribute(): Collection
    {
        if (!$this->areas) {
            return collect([]);
        }

        return $this->areas->map(function(Area $area) {
            return $area->area->areasLocatedIn;
        })
            ->filter()
            ->flatten()
            ->unique('id')  // unique preserves index keys
            ->values();     // return values so keys get lost / json_encode returns an array, not an object
    }

    // The areas where this instrument is available.
    // Overwritten by is_nationally_available if true
    // If owner is set, availability is limited by it
    public function getAvailableAreasAttribute(): Collection
    {
        // Nationally available => return all areas
        if ($this->is_nationally_available) {
            return Area::all();
        }

        if (!$this->relationLoaded('areas')) {
            $this->load('areas');
        }

        // If areas are specified return those
        if ($this->areasLocatedIn->count() > 0) {
            return $this->areasLocatedIn;
        }

        // Has owner. Return owner areas or subset
        /** @var Partnership|Region|Township $owner */
        $owner = $this->owner;
        // Has an owner with a area restriction
        if ($owner && $owner->areasLocatedIn) {
            return $owner->areas;
        }

        // Has no owner, or owner has no areas
        return collect([]);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /**
     * Even though this is a belongs to many relation
     * an instrument should probably belong to just one provider
     * actually the provider belongs to the instrument. But one provider can belong to multiple instruments
     *
     * @return BelongsToMany
     */
    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class, 'instrument_provider')->withTimestamps()->using(InstrumentProvider::class);
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function addresses(): MorphToMany
    {
        return $this->morphToMany(Address::class, 'addressable');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function registrationCodes(): HasMany
    {
        return $this->hasMany(RegistrationCode::class);
    }

    // Property selection (singular choice)
    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }

    // Property lists (multiple choice)
    public function groupForms(): BelongsToMany
    {
        return $this->belongsToMany(GroupForm::class, 'group_form_instrument')->withTimestamps()->using(GroupFormInstrument::class);
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'instrument_location')->withTimestamps()->using(InstrumentLocation::class);
    }

    public function targetGroups(): BelongsToMany
    {
        return $this->belongsToMany(TargetGroup::class, 'instrument_target_group')->withTimestamps()->using(InstrumentTargetGroup::class);
    }

    public function tiles(): BelongsToMany
    {
        return $this->belongsToMany(Tile::class, 'instrument_tile')->withTimestamps()->using(TileInstrument::class);
    }

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'instrument_theme')->withTimestamps()->using(ThemeInstrument::class);
    }

    public function clientCharacteristics(): BelongsToMany
    {
        return $this->belongsToMany(ClientCharacteristic::class, 'client_characteristic_instrument')->withTimestamps()->using(ClientCharacteristicInstrument::class);
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
}
