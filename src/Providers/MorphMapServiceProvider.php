<?php

namespace Vng\EvaCore\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Vng\EvaCore\Models\Address;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentTracker;
use Vng\EvaCore\Models\InstrumentType;
use Vng\EvaCore\Models\Link;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\Location;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Mutation;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Models\NewsItem;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Rating;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\RegionalParty;
use Vng\EvaCore\Models\RegistrationCode;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\Video;

class MorphMapServiceProvider extends ServiceProvider
{
    const MORPH_MAP = [
        'address' => Address::class,
        'client-characteristic' => ClientCharacteristic::class,
        'contact' => Contact::class,
        'download' => Download::class,
        'environment' => Environment::class,
        'group-form' => GroupForm::class,
        'implementation' => Implementation::class,
        'instrument' => Instrument::class,
        'instrument-type' => InstrumentType::class,
        'instrument-tracker' => InstrumentTracker::class,
        'link' => Link::class,
        'local-party' => LocalParty::class,
        'location' => Location::class,
        'manager' => Manager::class,
        'mutation' => Mutation::class,
        'national-party' => NationalParty::class,
        'neighbourhood' => Neighbourhood::class,
        'news-item' => NewsItem::class,
        'organisation' => Organisation::class,
        'partnership' => Partnership::class,
        'professional' => Professional::class,
        'provider' => Provider::class,
        'rating' => Rating::class,
        'region' => Region::class,
        'regional-party' => RegionalParty::class,
        'registration-code' => RegistrationCode::class,
        'target-group' => TargetGroup::class,
        'tile' => Tile::class,
        'township' => Township::class,
        'video' => Video::class,
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
        Relation::morphMap(static::MORPH_MAP);
    }
}
