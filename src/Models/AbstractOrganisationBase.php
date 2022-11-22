<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use ReflectionClass;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Interfaces\OrganisationEntityInterface;
use Vng\EvaCore\Observers\OrganisationEntityObserver;
use Vng\EvaCore\Traits\HasDynamicSlug;

abstract class AbstractOrganisationBase extends SearchableModel implements OrganisationEntityInterface
{
    use SoftDeletes {
        SoftDeletes::restore as softDeleteRestore;
    }
    use HasDynamicSlug;

    protected static function boot()
    {
        parent::boot();
        static::observe(OrganisationEntityObserver::class);
    }

    public function getTypeAttribute(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    public function getOrganisationType(): string
    {
        return $this->getTypeAttribute();
    }

    public function getOrganisationClass(): string
    {
        return get_class($this);
    }

    public function getOwnerType(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function hasMember(Model $manager): bool
    {
        if ($manager instanceof IsManagerInterface) {
            $manager = $manager->getManager();
        }
        return $this->getOrganisation()->hasMember($manager);
    }

    public function delete()
    {
        if ($this->isForceDeleting()) {
            $this->organisation()->withTrashed()->forceDelete();
        } else {
            $this->organisation()->delete();
        }
        parent::delete();
    }

    public function restore()
    {
        $this->organisation()->withTrashed()->restore();
        $this->softDeleteRestore();
    }
}

