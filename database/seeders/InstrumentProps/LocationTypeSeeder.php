<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Models\LocationType;
use Illuminate\Database\Seeder;

class LocationTypeSeeder extends Seeder
{
    public function run(): void
    {
        LocationType::withoutEvents(function () {
            $typeUitvoeringslocaties = Codelijsten::get('TypeUitvoeringsLocaties');
            foreach ($typeUitvoeringslocaties as $codeTypeUitvoeringslocatie => $naamTypeUitvoeringslocatie) {
                LocationType::query()->updateOrCreate(
                    ['name' => $naamTypeUitvoeringslocatie], // flip once prod has a code assigned to every uitvoeringslocatie
                    ['code' => $codeTypeUitvoeringslocatie], // should be leading
                );
            }

//            LocationType::query()->updateOrCreate([
//                'name' => 'Adres',
//            ]);
//            LocationType::query()->updateOrCreate([
//                'name' => 'Aanbieder',
//            ]);
//            LocationType::query()->updateOrCreate([
//                'name' => 'Werkgever',
//            ]);
//            LocationType::query()->updateOrCreate([
//                'name' => 'Gemeente',
//            ]);
//            LocationType::query()->updateOrCreate([
//                'name' => 'Klant thuis',
//            ]);
        });
    }
}
