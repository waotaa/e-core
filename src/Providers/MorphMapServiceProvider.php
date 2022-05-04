<?php

namespace Vng\EvaCore\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Models\Township;

class MorphMapServiceProvider extends ServiceProvider
{
    const MORPH_MAP = [
        'contact' => Contact::class,
        'instrument' => Instrument::class,
        'national-party' => NationalParty::class,
        'partnership' => Partnership::class,
        'provider' => Provider::class,
        'region' => Region::class,
        'tile' => Tile::class,
        'township' => Township::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setMorphMap();
    }

    private function setMorphMap()
    {
        Relation::enforceMorphMap(static::MORPH_MAP);
    }
}
