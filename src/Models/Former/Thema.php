<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thema extends Model
{
    use SoftDeletes;

    protected $table = 'themas';

    protected $guarded = [];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            if (!app()->runningInConsole() && request()->user()->is_admin != true) {
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
        return $this->belongsToMany(Instrument::class, 'thema_instrument', 'thema_id', 'instrument_id')->using(ThemaInstrument::class);
    }
}
