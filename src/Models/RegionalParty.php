<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Vng\EvaCore\ElasticResources\RegionalPartyResource;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\Traits\AreaTrait;
use Vng\EvaCore\Traits\HasDynamicSlug;
use Vng\EvaCore\Traits\IsOwner;

class RegionalParty extends SearchableModel implements IsOwnerInterface, AreaInterface
{
    use SoftDeletes, HasFactory, IsOwner, HasDynamicSlug, AreaTrait;

    protected $table = 'regional_parties';

    protected string $elasticResource = RegionalPartyResource::class;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function getParentAreas(): ?Collection
    {
        return $this->region->getParentAreas();
    }

    public function getOwnAreas(): Collection
    {
        return $this->region->getOwnAreas();
    }

    public function getChildAreas(): ?Collection
    {
        return $this->region->getChildAreas();
    }
}

