<?php

namespace Vng\EvaCore\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Observers\OrganisationEntityObserver;

trait OrganisationEntity
{
    protected static function bootOrganisationEntity()
    {
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
}
