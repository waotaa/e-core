<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Models\ClientCharacteristic;
use Illuminate\Database\Seeder;

class ClientCharacteristicSeeder extends Seeder
{
    public function run(): void
    {
        ClientCharacteristic::withoutEvents(function () {
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Psychosociale aandachtspunten',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Lichamelijke aandachtspunten',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Praktische aandachtspunten',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Sociale- en culturele vaardigheden',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Basisvaardigheden',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Basis werknemersvaardigheden',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Toekomstige werknemersvaardigheden',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Sollicitatievaardigheden',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Diplomeren, certificeren, en praktijkverklaring',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Werkervaring',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Beroepskeuze, beroepsvoorkeur, en beroepsoriëntatie',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Willen en geloven in eigen kunnen',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Zelfregie / zelfsturing',
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Sociale steun en netwerk',
            ]);
        });
    }
}
