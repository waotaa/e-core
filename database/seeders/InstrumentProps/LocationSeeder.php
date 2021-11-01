<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::query()->updateOrCreate([
            'name' => 'Adres',
        ]);
        Location::query()->updateOrCreate([
            'name' => 'Aanbieder',
        ]);
        Location::query()->updateOrCreate([
            'name' => 'Werkgever',
        ]);
        Location::query()->updateOrCreate([
            'name' => 'Gemeente',
        ]);
        Location::query()->updateOrCreate([
            'name' => 'Klant thuis',
        ]);
    }
}
