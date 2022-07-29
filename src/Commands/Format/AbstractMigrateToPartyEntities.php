<?php

namespace Vng\EvaCore\Commands\Format;

use Illuminate\Console\Command;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\RegionalParty;
use Vng\EvaCore\Models\Township;

abstract class AbstractMigrateToPartyEntities extends Command
{
    public function findOrCreateLocalParty(Township $township): LocalParty
    {
        /** @var ?LocalParty $localParty */
        $localParty = LocalParty::query()->where('township_id', $township->id)->first();

        if (!$localParty) {
            $localParty = new LocalParty([
                'name' => $township->getName(),
            ]);
            $localParty->township()->associate($township);
            $localParty->save();
        }

        return $localParty;
    }

    public function findOrCreateRegionalParty(Region $region): RegionalParty
    {
        /** @var ?RegionalParty $regionalParty */
        $regionalParty = RegionalParty::query()->where('region_id', $region->id)->first();

        if (!$regionalParty) {
            $regionalParty = new RegionalParty([
                'name' => $region->getName(),
            ]);
            $regionalParty->region()->associate($region);
            $regionalParty->save();
        }

        return $regionalParty;
    }
}
