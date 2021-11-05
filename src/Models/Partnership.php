<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\Traits\HasSlug;
use Vng\EvaCore\Traits\IsOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Partnership extends Model implements IsOwnerInterface
{
    use SoftDeletes, HasFactory, IsOwner, HasSlug;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function townships(): BelongsToMany
    {
        return $this->belongsToMany(Township::class);
    }

    public function featuredByEnvironment(): MorphOne
    {
        return $this->morphOne(Environment::class, 'featured_association');
    }

    public function getAreasAttribute(): Collection
    {
        if (!$this->relationLoaded('townships')) {
            $this->load('townships');
        }
        return collect($this->townships)->map(function(Township $township) {
            return $township->area;
        })->filter();
    }

    /**
     * Returns all areas this partnership is located in
     * For a partnership that means the townships itself and their parent region
     * Change when provinces are added
     * @return Collection
     */
    public function getAreasLocatedInAttribute(): Collection
    {
        $areas = $this->getAreasAttribute();
        $this->townships()->each(function(Township $township) use ($areas) {
            $areas->add($township->region->area);
        });
        return $areas;
    }
}
