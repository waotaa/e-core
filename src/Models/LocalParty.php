<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Vng\EvaCore\ElasticResources\LocalPartyResource;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Traits\AreaTrait;

class LocalParty extends AbstractOrganisationBase implements AreaInterface
{
    use HasFactory, AreaTrait;

    protected $table = 'local_parties';

    protected string $elasticResource = LocalPartyResource::class;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function township(): BelongsTo
    {
        return $this->belongsTo(Township::class);
    }

    public function getParentAreas(): ?Collection
    {
        return $this->township->getParentAreas();
    }

    public function getOwnAreas(): Collection
    {
        return $this->township->getOwnAreas();
    }

    public function getChildAreas(): ?Collection
    {
        return $this->township->getChildAreas();
    }
}

