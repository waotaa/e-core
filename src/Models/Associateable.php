<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
