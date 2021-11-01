<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TargetGroup extends Model
{
    use SoftDeletes;

    protected $table = 'target_groups';

    protected $fillable = [
        'description',
        'custom',
    ];

    protected $casts = [
        'custom' => 'boolean',
    ];

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'instrument_target_group')->withTimestamps()->using(InstrumentTargetGroup::class);
    }

    public function getOwningInstrumentAttribute(): ?Instrument
    {
        if (!$this->custom) {
            return null;
        }
        return $this->instruments()->orderByPivot('created_at', 'asc')->first();
    }
}
