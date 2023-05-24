<?php


namespace Vng\EvaCore\Services;

use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Models\Township;

class TownshipReallocationService
{
    public static function transfer(Township $currentTownship, Township $newTownship)
    {
        $currentTownship->localParties()->each(
            fn (LocalParty $localParty) => $localParty->township()->associate($newTownship)->saveQuietly()
        );

        $currentTownship->neighbourhoods()->each(
            fn (Neighbourhood $neighbourhood) => $neighbourhood->township()->associate($newTownship)->saveQuietly()
        );
    }
}
