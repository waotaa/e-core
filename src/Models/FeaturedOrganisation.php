<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class FeaturedOrganisation extends MorphPivot
{
    public $incrementing = true;
    protected $table = 'featured_organisations';

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function environment()
    {
        return $this->belongsTo(Environment::class);
    }

    public function getNameAttribute()
    {
        return $this->organisation->name;
    }

    public function getTypeAttribute(): ?string
    {
        if (is_null($this->organisation)) {
            return null;
        }
        return get_class($this->association);
    }
}

