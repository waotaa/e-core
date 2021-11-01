<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Contracts\HasMembers as HasMembersContract;
use Vng\EvaCore\Contracts\IsOwner as IsOwnerContract;
use Vng\EvaCore\Traits\HasMembers;
use Vng\EvaCore\Traits\HasSlug;
use Vng\EvaCore\Traits\IsOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Township extends Model implements HasMembersContract, IsOwnerContract
{
    use HasFactory, SoftDeletes, HasSlug, IsOwner, HasMembers;

    protected $table = 'townships';

    protected $fillable = [
        'name',
        'slug',
        'code',
        'region_code',
        'description'
    ];

    // A Township belongs to a Region
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    // A Township is an area
    public function area(): MorphOne
    {
        return $this->morphOne(Area::class, 'area');
    }

    public function getAreasAttribute(): Collection
    {
        if (!$this->relationLoaded('area')) {
            $this->load('area');
        }

        return collect([
            $this->area,
        ]);
    }

    /**
     * Returns all areas this township is located in
     * For a township that means the township itself and it's parent region
     * Change when provinces are added
     * @return Collection
     */
    public function getAreasLocatedInAttribute(): Collection
    {
        $areas = $this->getAreasAttribute();
        if ($this->region && $this->region->area) {
            $areas->add($this->region->area,);
        }
        return $areas;
    }

    public function instruments(): BelongsToMany
    {
        return $this->area->instruments();
    }

    public function partnerships(): BelongsToMany
    {
        return $this->belongsToMany(Partnership::class);
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
