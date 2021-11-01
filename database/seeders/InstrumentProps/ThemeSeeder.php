<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Models\Theme;
use Vng\EvaCore\Models\Tile;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    private function findTileIdByKey($key)
    {
        $tile = Tile::query()->where('key', $key)->firstOrFail();
        return $tile->id;
    }

    public function run(): void
    {
        // Diagnose
        Theme::query()->updateOrCreate([
            'description' => 'Basisvaardigheden',
        ], [
            'custom' => false
        ])->tiles()->attach([
            $this->findTileIdByKey('diagnostiek'),
            $this->findTileIdByKey('maatschappelijk_fit')
        ]);

        Theme::query()->updateOrCreate([
            'description' => 'Levensgebieden',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('diagnostiek'));

        Theme::query()->updateOrCreate([
            'description' => 'Afstand naar werk',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('diagnostiek'));

        Theme::query()->updateOrCreate([
            'description' => 'Belastbaarheid',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('diagnostiek'));

        Theme::query()->updateOrCreate([
            'description' => 'Medisch onderzoeken',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('diagnostiek'));


        Theme::query()->updateOrCreate([
            'description' => 'Participatie',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('maatschappelijk_fit'));

        Theme::query()->updateOrCreate([
            'description' => 'Financiën',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('maatschappelijk_fit'));

        Theme::query()->updateOrCreate([
            'description' => 'Mentale & fysieke gezondheid',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('maatschappelijk_fit'));

        Theme::query()->updateOrCreate([
            'description' => 'Ondersteuningsvragen',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('maatschappelijk_fit'));


        Theme::query()->updateOrCreate([
            'description' => 'Werknemersvaardigheden',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('werk_fit'));

        Theme::query()->updateOrCreate([
            'description' => 'Praktische belemmeringen',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('werk_fit'));

        Theme::query()->updateOrCreate([
            'description' => 'Uitstroomprofiel',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('werk_fit'));

        Theme::query()->updateOrCreate([
            'description' => 'Sollicitatie vaardigheden',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('werk_fit'));


        Theme::query()->updateOrCreate([
            'description' => 'Onderzoek en advies',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('orientatie'));

        Theme::query()->updateOrCreate([
            'description' => 'Doen en ervaren',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('orientatie'));


        Theme::query()->updateOrCreate([
            'description' => 'Certificaat',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('opleiden'));

        Theme::query()->updateOrCreate([
            'description' => 'Diplomeren',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('opleiden'));

        Theme::query()->updateOrCreate([
            'description' => 'Leerwerktrajecten',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('opleiden'));


        Theme::query()->updateOrCreate([
            'description' => 'Aanbod gericht',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('bemiddeling'));

        Theme::query()->updateOrCreate([
            'description' => 'Vraag gericht',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('bemiddeling'));

        Theme::query()->updateOrCreate([
            'description' => 'Starten eigen onderneming',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('bemiddeling'));


        Theme::query()->updateOrCreate([
            'description' => 'Instrument werknemer',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('plaatsing'));

        Theme::query()->updateOrCreate([
            'description' => 'Instrument werkgever',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('plaatsing'));


        Theme::query()->updateOrCreate([
            'description' => 'Jobcoaching',
        ], [
            'custom' => false
        ])->tiles()->attach($this->findTileIdByKey('nazorg'));
    }
}
