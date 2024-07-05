<?php

namespace Database\Seeders;

use Database\Seeders\Admin\TileSeeder;
use Database\Seeders\InstrumentProps\ClientCharacteristicSeeder;
use Database\Seeders\InstrumentProps\GroupFormSeeder;
use Database\Seeders\InstrumentProps\ImplementationSeeder;
use Database\Seeders\InstrumentProps\TargetGroupSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ClientCharacteristicSeeder::class);
        $this->call(GroupFormSeeder::class);
        $this->call(ImplementationSeeder::class);
        $this->call(TargetGroupSeeder::class);
        $this->call(TileSeeder::class);
    }
}
