<?php

namespace Database\Seeders\InstrumentProps;

use Illuminate\Database\Seeder;
use Vng\EvaCore\Models\InstrumentType;

class InstrumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        InstrumentType::withoutEvents(function () {
            InstrumentType::query()->updateOrCreate([
                'key' => 'IT-1',
            ], [
                'name' => 'Werkzoekende-dienstverlening'
            ]);
            InstrumentType::query()->updateOrCreate([
                'key' => 'IT-2',
            ], [
                'name' => 'Werkgevers-dienstverlening'
            ]);

    //        InstrumentType::query()->updateOrCreate([
    //            'key' => 'IT-3',
    //        ], [
    //            'name' => 'Mobiliteits-dienstverleninig'
    //        ]);
            InstrumentType::query()->where([
                'key' => 'IT-3',
            ])->delete();
        });
    }
}
