<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vng\EvaCore\Traits\HasOwner;

class TargetGroup extends Model
{
    use SoftDeletes, HasOwner;

    protected $table = 'target_groups';

    protected $fillable = [
        'description',
        'code',
        'custom',
    ];

    protected $attributes = [
        'custom' => true,
    ];

    protected $casts = [
        'custom' => 'boolean',
    ];

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'instrument_target_group')
            ->withTimestamps()
            ->using(InstrumentTargetGroup::class);
    }

    public function getOwningInstrumentAttribute(): ?Instrument
    {
        if (!$this->getAttribute('custom')) {
            return null;
        }
        return $this->instruments()->orderByPivot('created_at', 'asc')->first();
    }
}
