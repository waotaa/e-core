<?php

namespace Vng\EvaCore\Models;

use Vng\EvaCore\ElasticResources\ThemeResource;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Theme extends SearchableModel
{
    use SoftDeletes;

    protected $table = 'themes';
    protected string $elasticResource = ThemeResource::class;

    protected $fillable = [
        'description',
        'custom',
    ];

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'instrument_theme')->using(ThemeInstrument::class);
    }

    public function getOwningInstrumentAttribute(): ?Instrument
    {
        if (!$this->custom) {
            return null;
        }
        return $this->instruments()->orderBy('created_at', 'asc')->first();
    }
}
