<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AfstandTotWerk extends Model
{
    use SoftDeletes;

    protected $table = 'afstand_tot_werk';

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            if (request()->user()->is_admin != true) {
                $model->owner_id = request()->user()->role->access_id;
                $model->owner_type = request()->user()->role->access_type;
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function instrument()
    {
        return $this->belongsToMany(Instrument::class, 'afstand_tot_werk_instrument', 'afstand_tot_werk_id', 'instrument_id')->using(AfstandTotWerkInstrument::class);
    }
}
