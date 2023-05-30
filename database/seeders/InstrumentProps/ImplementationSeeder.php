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
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Workshop',
            ], [
                'code' => 'UV02',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Vragenlijst',
            ], [
                'code' => 'UV03',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'E-Learning',
            ], [
                'code' => 'UV04',
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Coaching',
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
                'name' => 'Werk/Stage',
            ], [
                'code' => 'UV07',
                'custom' => false
            ]);
        });
    }
}
