<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\ElasticResources\RegionResource;
use Vng\EvaCore\Traits\AreaTrait;
use Vng\EvaCore\Traits\HasContacts;
use Vng\EvaCore\Traits\HasDynamicSlug;
use Vng\EvaCore\Traits\IsOwner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Region extends SearchableModel implements IsOwnerInterface, AreaInterface
{
    use HasFactory, SoftDeletes, HasDynamicSlug, IsOwner, HasContacts, AreaTrait;

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

    public function getParentAreas(): ?Collection
    {
        return null;
    }

    public function getChildAreas(): ?Collection
    {
        return $this->townships;
    }

    public function featuredByEnvironment(): MorphOne
    {
        return $this->morphOne(Environment::class, 'featured_association');
    }
}
