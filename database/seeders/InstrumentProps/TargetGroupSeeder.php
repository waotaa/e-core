<?php

namespace Database\Seeders\InstrumentProps;

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
            // Name changes
            TargetGroup::query()->where([
                'description' => 'Algemeen'
            ])->update([
                'description' => 'Werkzoekende algemeen',
            ]);


            TargetGroup::query()->where([
                'description' => 'Jongere tot 27 jaar',
            ])->update([
                'description' => 'Jongeren tot 27 jaar'
            ]);

            TargetGroup::query()->where([
                'description' => 'Arbeidsbeperkten'
            ])->update([
                'description' => 'Kandidaat met arbeidsbeperking',
            ]);
            TargetGroup::query()->where([
                'description' => 'Kandidaat met arbeidsbeperking',
            ])->update([
                'description' => '(Vermoedelijk) Arbeidsbeperkten'
            ]);

            TargetGroup::query()->where([
                'description' => 'Mensen met een migratieachtergrond'
            ])->update([
                'description' => 'Kandidaat met een migratieachtergrond',
            ]);
            TargetGroup::query()->where([
                'description' => 'Kandidaat met een migratieachtergrond'
            ])->update([
                'description' => 'Migranten',
            ]);

            // Deprecated (format 1)
            TargetGroup::query()->where([
                'description' => 'Werkzoekende algemeen'
            ])->update([
                'description' => 'Werkzoekende algemeen v1',
            ]);
            TargetGroup::query()->where([
                'description' => 'Werkzoekende algemeen v1'
            ])->delete();

            TargetGroup::query()->where([
                'description' => 'Kandidaat met psychische problematiek'
            ])->update([
                'description' => 'Kandidaat met psychische problematiek v1',
            ]);
            TargetGroup::query()->where([
                'description' => 'Kandidaat met psychische problematiek v1'
            ])->delete();

            TargetGroup::query()->where([
                'description' => 'Alleenstaande ouder'
            ])->update([
                'description' => 'Alleenstaande ouder v1',
            ]);
            TargetGroup::query()->where([
                'description' => 'Alleenstaande ouder v1'
            ])->delete();


            // The data set
            TargetGroup::query()->updateOrCreate([
                'description' => 'Jongeren tot 27 jaar'
            ], [
                'code' => 'ED01',
                'custom' => false,
            ]);

            TargetGroup::query()->updateOrCreate([
                'description' => '50-plussers'
            ], [
                'code' => 'ED02',
                'custom' => false,
            ]);

            TargetGroup::query()->updateOrCreate([
                'description' => '(Vermoedelijk) Verstandelijke beperking (IQ 50-85)'
            ], [
                'code' => 'ED03',
                'custom' => false,
            ]);

            TargetGroup::query()->updateOrCreate([
                'description' => '(Vermoedelijk) Arbeidsbeperkten'
            ], [
                'code' => 'ED04',
                'custom' => false,
            ]);

            TargetGroup::query()->updateOrCreate([
                'description' => 'Statushouders'
            ], [
                'code' => 'ED05',
                'custom' => false,
            ]);

            TargetGroup::query()->updateOrCreate([
                'description' => 'Migranten'
            ], [
                'code' => 'ED06',
                'custom' => false,
            ]);

            TargetGroup::query()->updateOrCreate([
                'description' => 'Ondernemers'
            ], [
                'code' => 'ED07',
                'custom' => false,
            ]);

            TargetGroup::query()->updateOrCreate([
                'description' => 'Dak- en thuislozen'
            ], [
                'code' => 'ED08',
                'custom' => false,
            ]);
        });
    }
}
