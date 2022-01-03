<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\Traits\AreaTrait;
use Vng\EvaCore\Traits\HasDynamicSlug;
use Vng\EvaCore\Traits\IsOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Township extends Model implements IsOwnerInterface, AreaInterface
{
    use HasFactory, SoftDeletes, HasDynamicSlug, IsOwner, AreaTrait;

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

    // A Township can have many parts
    public function neighbourhoods(): HasMany
    {
        return $this->hasMany(Neighbourhood::class);
    }

    // A Township is an area
    public function area(): MorphOne
    {
        return $this->morphOne(Area::class, 'area');
    }

    public function getParentAreas(): ?Collection
    {
        return collect([$this->region]);
    }

    public function getChildAreas(): ?Collection
    {
        return $this->neighbourhoods;
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
