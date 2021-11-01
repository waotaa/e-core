<?php

namespace Database\Seeders\Environments;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Township;
use Illuminate\Database\Seeder;

class RsdPartnershipSeeder extends Seeder
{
    /**
     * php artisan db:seed --class="Database\Seeders\Environments\RsdPartnershipSeeder"
     * php artisan eva:move-items rsd-de-liemers environment rsd-de-liemers partnership
     */
    public function run(): void
    {
        /** @var Partnership $partnership */
        $partnership = Partnership::query()->firstOrCreate([
            'name' => 'RSD de Liemers',
        ]);

        $townships = Township::query()->whereIn('name', [
            'Duiven',
            'Westervoort',
            'Zevenaar',
        ])->get();

        $partnership->townships()->sync($townships->pluck('id'));

        /** @var Environment $environment */
        $environment = Environment::query()->where(['name' => 'RSD de Liemers'])->firstOrFail();
        $environment->featuredAssociation()->associate($partnership);
        $environment->save();
    }
}
