<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Vng\EvaCore\ElasticResources\LocalPartyResource;
use Vng\EvaCore\Interfaces\AreaInterface;
use Vng\EvaCore\Interfaces\OrganisationEntityInterface;
use Vng\EvaCore\Interfaces\IsOwnerInterface;
use Vng\EvaCore\Observers\OrganisationEntityObserver;
use Vng\EvaCore\Traits\AreaTrait;
use Vng\EvaCore\Traits\HasDynamicSlug;
use Vng\EvaCore\Traits\OrganisationEntity;
use Vng\EvaCore\Traits\IsOwner;

abstract class AbstractOrganisationBase extends SearchableModel implements IsOwnerInterface, OrganisationEntityInterface
{
    use SoftDeletes {
        restore as softDeleteRestore;
        forceDelete as softDeleteForceDelete;
    }
    use IsOwner, HasDynamicSlug;

    protected static function boot()
    {
        parent::boot();
        static::observe(OrganisationEntityObserver::class);
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function delete()
    {
        $this->organisation->delete();
        parent::delete();
    }

    public function restore()
    {
        $this->organisation->restore();
        $this->softDeleteRestore();
    }

    public function forceDelete()
    {
        $this->organisation->forceDelete();
        $this->softDeleteForceDelete();
    }
}

