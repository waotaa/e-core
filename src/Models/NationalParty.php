<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Vng\EvaCore\ElasticResources\NationalPartyResource;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\Services\AreaService;
use Vng\EvaCore\Traits\AreaTrait;
use Vng\EvaCore\Traits\HasDynamicSlug;
use Vng\EvaCore\Traits\IsOwner;

class NationalParty extends SearchableModel implements IsOwnerInterface, AreaInterface
{
    use SoftDeletes, HasFactory, IsOwner, HasDynamicSlug, AreaTrait;

    protected $table = 'national_parties';

    protected string $elasticResource = NationalPartyResource::class;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function getParentAreas(): ?Collection
    {
        return null;
    }

    public function getChildAreas(): ?Collection
    {
        return AreaService::getNationalAreas();
    }
}

