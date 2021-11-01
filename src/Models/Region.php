<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Contracts\HasMembers as HasMembersContract;
use Vng\EvaCore\Contracts\IsOwner as IsOwnerContract;
use Vng\EvaCore\ElasticResources\RegionResource;
use Vng\EvaCore\Traits\HasContacts;
use Vng\EvaCore\Traits\HasMembers;
use Vng\EvaCore\Traits\HasSlug;
use Vng\EvaCore\Traits\IsOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Region extends SearchableModel implements HasMembersContract, IsOwnerContract
{
    use HasFactory, SoftDeletes, HasSlug, IsOwner, HasMembers, HasContacts;

    protected $table = 'regions';
    protected string $elasticResource = RegionResource::class;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'color',
        'description',
        'cooperation_partners',
    ];

    // A Region has many gemeenten
    public function townships(): HasMany
    {
        return $this->hasMany(Township::class);
    }

    // A Region is an area
    public function area(): MorphOne
    {
        return $this->morphOne(Area::class, 'area');
    }

    public function getAreasAttribute(): Collection
    {
        if (!$this->relationLoaded('area')) {
            $this->load('area');
        }
        if (!$this->relationLoaded('townships')) {
            $this->load('townships');
        }

        if ($this->townships->isEmpty()) {
            return collect([$this->area]);
        }

        $areas = collect($this->townships)->map(function(Township $township) {
            return $township->area;
        })->filter();

        if (!is_null($this->area)) {
            $areas->add($this->area);
        }
        return $areas;
    }

    /**
     * Returns all areas this region is located in
     * For a region that means it owns region
     * Change when provinces are added
     * @return Collection
     */
    public function getAreasLocatedInAttribute(): Collection
    {
        if (!is_null($this->area)) {
            return collect([]);
        }
        return collect([$this->area]);
    }

    public function instruments(): BelongsToMany
    {
        return $this->area->instruments();
    }

    public function featuredByEnvironment(): MorphOne
    {
        return $this->morphOne(Environment::class, 'featured_association');
    }

    public function delete()
    {
        $this->area()->delete();
        return parent::delete();
    }
}
