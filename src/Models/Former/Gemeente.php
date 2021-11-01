<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Gemeente extends Model
{
    use SoftDeletes;

    protected $table = 'gemeentes';

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::saving(function($model) {
            $model->slug = (string) Str::slug($model->naam);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function regios()
    {
        return $this->belongsToMany(Regio::class, 'gemeente_regio', 'gemeente_id', 'regio_id')->using(GemeenteRegio::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function instrument()
    {
        return $this->belongsToMany(Instrument::class, 'gemeente_instrument', 'gemeente_id', 'instrument_id')->using(GemeenteInstrument::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function role()
    {
        return $this->morphOne(Role::class, 'access');
    }
}
