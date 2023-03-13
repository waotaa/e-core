<?php

namespace Vng\EvaCore\Models;

use Database\Factories\TileFactory;
use Vng\EvaCore\ElasticResources\TileResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tile extends SearchableModel
{
    use SoftDeletes, HasFactory;

    protected $table = 'tiles';
    protected string $elasticResource = TileResource::class;

    protected $fillable = [
        'name',
        'sub_title',
        'excerpt',
        'description',
        'list',
        'crisis_description',
        'crisis_services',
        'key',
        'position',
    ];

    protected $casts = [
        'position' => 'json',
    ];

    protected static function newFactory()
    {
        return TileFactory::new();
    }

    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'instrument_tile')->using(InstrumentTile::class);
    }
}
