<?php

namespace Vng\EvaCore\Models\Former;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Aanbieder extends Model
{
    use SoftDeletes;

    protected $table = 'aanbieders';

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });

        self::saving(function ($model) {
            if (!app()->runningInConsole() && request()->user()->is_admin != true) {
                $model->owner_id = request()->user()->role->access_id;
                $model->owner_type = request()->user()->role->access_type;
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instrument()
    {
        return $this->hasMany(Instrument::class);
    }
}
