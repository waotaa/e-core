<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @deprecated
 * This was the previous relationship between a user and an organisation
 * Once all parties migrated to orchid this can be removed
 */
abstract class Associateable extends MorphPivot
{
    public $incrementing = true;
    protected $table = 'associateables';

    abstract public function user(): BelongsTo;

    public function association(): MorphTo
    {
        return $this->morphTo('associateable');
    }

    public function getAssociationNameAttribute()
    {
        return $this->association->name;
    }

    public function getAssociationTypeAttribute(): ?string
    {
        if (is_null($this->association)) {
            return null;
        }
        return $this->association->getOwnerType();
    }
}
