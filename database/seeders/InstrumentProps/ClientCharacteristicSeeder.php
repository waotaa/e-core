<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Models\ClientCharacteristic;
use Illuminate\Database\Seeder;

/**
 * Klantkenmerken - KK
 */
class ClientCharacteristicSeeder extends Seeder
{
    public function run(): void
    {
        ClientCharacteristic::withoutEvents(function () {
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Psychosociale aandachtspunten',
            ], [
                'code' => 'KK01'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Lichamelijke aandachtspunten',
            ], [
                'code' => 'KK02'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Praktische aandachtspunten',
            ], [
                'code' => 'KK03'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Sociale- en culturele vaardigheden',
            ], [
                'code' => 'KK04'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Basisvaardigheden',
            ], [
                'code' => 'KK05'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Basis werknemersvaardigheden',
            ], [
                'code' => 'KK06'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Toekomstige werknemersvaardigheden',
            ], [
                'code' => 'KK07'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Sollicitatievaardigheden',
            ], [
                'code' => 'KK08'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Diplomeren, certificeren, en praktijkverklaring',
            ], [
                'code' => 'KK09'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Werkervaring',
            ], [
                'code' => 'KK10'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Beroepskeuze, beroepsvoorkeur, en beroepsoriëntatie',
            ], [
                'code' => 'KK11'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Willen en geloven in eigen kunnen',
            ], [
                'code' => 'KK12'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Zelfregie / zelfsturing',
            ], [
                'code' => 'KK13'
            ]);
            ClientCharacteristic::query()->updateOrCreate([
                'name' => 'Sociale steun en netwerk',
            ], [
                'code' => 'KK14'
            ]);
        });
    }
}
