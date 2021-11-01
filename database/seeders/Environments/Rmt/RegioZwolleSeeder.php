<?php

namespace Database\Seeders\Environments\Rmt;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Region;
use Illuminate\Database\Seeder;

class RegioZwolleSeeder extends Seeder
{
    public function run(): void
    {
        $region = Region::query()->where('slug', 'regio-zwolle')->firstOrFail();

        /** @var Environment $environment */
        Environment::query()->create([
            'name' => 'Regio Zwolle',
            'color_primary' => '#e64010',
            'color_secondary' => '#202020',
            'featured_association_id' => $region->id,
            'featured_association_type' => get_class($region)
        ]);
    }
}
