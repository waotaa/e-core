<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Models\GroupForm;
use Illuminate\Database\Seeder;

class GroupFormSeeder extends Seeder
{
    public function run(): void
    {
        GroupForm::query()->updateOrCreate([
            'name' => 'Individueel',
        ], [
            'custom' => false
        ]);
        GroupForm::query()->updateOrCreate([
            'name' => 'Groep',
        ], [
            'custom' => false
        ]);
    }
}
