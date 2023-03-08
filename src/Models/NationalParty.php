<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Vng\EvaCore\ElasticResources\NationalPartyResource;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Observers\NationalPartyObserver;
use Vng\EvaCore\Services\AreaService;
use Vng\EvaCore\Traits\AreaTrait;

class NationalParty extends AbstractOrganisationBase implements AreaInterface
{
    use HasFactory, AreaTrait;

    protected $table = 'national_parties';

    protected string $elasticResource = NationalPartyResource::class;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(NationalPartyObserver::class);
    }

    public function getParentAreas(): ?Collection
    {
        return null;
    }

    public function getOwnAreas(): Collection
    {
        return AreaService::getNationalAreas();
    }

    public function getChildAreas(): ?Collection
    {
        return $this->getOwnAreas()
            ->map(fn (AreaInterface $area) => $area->getChildAreas())
            ->flatten()
            ->unique(fn (AreaInterface $area) => $area->getAreaIdentifier());
    }
}

