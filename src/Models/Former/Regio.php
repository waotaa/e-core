<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regio extends Model
{
    use SoftDeletes;

    protected $table = 'regios';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function gemeentes()
    {
        return $this->belongsToMany(Gemeente::class, 'gemeente_regio', 'regio_id', 'gemeente_id')->using(GemeenteRegio::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function instrument()
    {
        return $this->belongsToMany(Instrument::class, 'regio_instrument', 'regio_id', 'instrument_id')->using(RegioInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function role()
    {
        return $this->morphOne(Role::class, 'access');
    }
}
