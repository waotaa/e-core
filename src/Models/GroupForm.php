<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vng\EvaCore\Observers\GroupFormObserver;

class GroupForm extends Model
{
    use SoftDeletes;

    protected $table = 'group_forms';

    protected $fillable = [
        'name',
        'custom'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(GroupFormObserver::class);
    }

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'group_form_instrument')->withTimestamps()->using(GroupFormInstrument::class);
    }
}
