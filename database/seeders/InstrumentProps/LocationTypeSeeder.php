<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Models\LocationType;
use Illuminate\Database\Seeder;

class LocationTypeSeeder extends Seeder
{
    public function run(): void
    {
        LocationType::withoutEvents(function () {
            LocationType::query()->updateOrCreate([
                'name' => 'Adres',
            ]);
            LocationType::query()->updateOrCreate([
                'name' => 'Aanbieder',
            ]);
            LocationType::query()->updateOrCreate([
                'name' => 'Werkgever',
            ]);
            LocationType::query()->updateOrCreate([
                'name' => 'Gemeente',
            ]);
            LocationType::query()->updateOrCreate([
                'name' => 'Klant thuis',
            ]);
        });
    }
}
