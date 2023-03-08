<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Models\Implementation;
use Illuminate\Database\Seeder;

class ImplementationSeeder extends Seeder
{
    public function run(): void
    {
        Implementation::withoutEvents(function () {
            Implementation::query()->updateOrCreate([
                'name' => 'Training',
            ], [
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Workshop',
            ], [
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Vragenlijst',
            ], [
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'E-Learning',
            ], [
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Coaching',
            ], [
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Onderzoek',
            ], [
                'custom' => false
            ]);
            Implementation::query()->updateOrCreate([
                'name' => 'Werk/Stage',
            ], [
                'custom' => false
            ]);
        });
    }
}
