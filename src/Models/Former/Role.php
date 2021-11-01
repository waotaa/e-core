<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instrument()
    {
        return $this->hasMany(Instrument::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function access()
    {
        return $this->morphTo('access');
    }
}
