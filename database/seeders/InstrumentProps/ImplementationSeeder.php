<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Models\Implementation;
use Illuminate\Database\Seeder;

/**
 * Uitvoeringsvorm - UV
 */
class ImplementationSeeder extends Seeder
{
    public function run(): void
    {
        Implementation::withoutEvents(function () {
            Implementation::query()->updateOrCreate([
                'name' => 'Training',
            ], [
                'code' => 'UV01',
                'custom' => true
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Workshop',
            ], [
                'code' => 'UV02',
                'custom' => true
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Vragenlijst',
            ], [
                'code' => 'UV03',
                'custom' => true
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'E-Learning',
            ], [
                'code' => 'UV04',
                'custom' => true
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Coaching',
            ], [
                'code' => 'UV05',
                'custom' => true
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Onderzoek',
            ], [
                'code' => 'UV06',
                'custom' => true
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Werk/Stage',
            ], [
                'code' => 'UV07',
                'custom' => true
            ]);
//            Implementation::query()->where([
//                'custom' => true,
//            ])->delete();


            // The data set
            Implementation::query()->updateOrCreate([
                'name' => '(Bij)scholing',
            ], [
                'code' => 'UV01',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Opleiding',
            ], [
                'name' => 'Opleiding',
                'code' => 'UV02',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Leerwerktraject',
            ], [
                'name' => 'Leerwerktraject',
                'code' => 'UV03',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => '(Vrijwilligers)werk',
            ], [
                'code' => 'UV04',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Vragenlijst',
            ], [
                'code' => 'UV05',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Onderzoek',
            ], [
                'code' => 'UV06',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Coaching',
            ], [
                'code' => 'UV07',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Subsidie/Voucher',
            ], [
                'code' => 'UV08',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Tolken/Vertalen',
            ], [
                'code' => 'UV09',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Verwijzing',
            ], [
                'code' => 'UV10',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Materiele ondersteuning',
            ], [
                'code' => 'UV11',
                'custom' => false
            ]);
        });
    }
}
