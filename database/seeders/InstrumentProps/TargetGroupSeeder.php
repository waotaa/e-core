<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Models\TargetGroup;
use Illuminate\Database\Seeder;

/**
 * Eva Doelgroep - ED
 */
class TargetGroupSeeder extends Seeder
{
    public function run(): void
    {
        TargetGroup::withoutEvents(function () {
            $doelgroepen = Codelijsten::getDoelgroepenEva();
            foreach ($doelgroepen as $codeDoelgroep => $naamDoelgroep) {
                TargetGroup::query()->updateOrCreate(
                    ['code' => $codeDoelgroep],
                    [
                        'description' => $naamDoelgroep,
                        'custom' => false
                    ]
                );
            }
        });
    }
}
