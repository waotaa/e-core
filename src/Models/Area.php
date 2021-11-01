<?php

namespace Vng\EvaCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'area_type',
    ];

    protected $appends = [
        'name',
        'area_kind_class',
        'area_kind',
        'area_kind_and_name',
    ];

    public function getNameAttribute() {
        return $this->area->name;
    }

    public function getAreaKindClassAttribute() {
        return get_class($this->area);
    }

    public function getAreaKindAttribute() {
        if ($this->area_kind_class === Township::class) {
            return 'Gemeente';
        }
        if ($this->area_kind_class === Region::class) {
            return 'Regio';
        }
        return null;
    }

    public function getAreaKindAndNameAttribute() {
        $name = $this->area_kind . ' - ' . $this->name;
        return $name;
    }

    public function getAreaPathAttribute()
    {
        $name = '';
        if ($this->area_kind_class === Township::class) {
            $name .= $this->area->region->name . ' / ';
        }
        $name .= $this->name;
        $name .= ' (' . substr($this->area_kind, 0, 1) . ')';

        return $name;
    }

    // A Township or Region. In future maybe Province
    public function area(): MorphTo
    {
        return $this->morphTo();
    }

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'area_instrument')->withTimestamps()->using(AreaInstrument::class);
    }
}
