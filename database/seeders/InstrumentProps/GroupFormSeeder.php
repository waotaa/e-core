<?php

namespace Database\Seeders\InstrumentProps;

use Vng\EvaCore\Helpers\Codelijsten;
use Vng\EvaCore\Models\GroupForm;
use Illuminate\Database\Seeder;

/**
 * Groepsvormen - GV
 */
class GroupFormSeeder extends Seeder
{
    public function run(): void
    {


        GroupForm::withoutEvents(function () {
            $groepsvormen = Codelijsten::get('Groepsvormen');
            foreach ($groepsvormen as $codeGroepsvorm => $naamGroepsvorm) {
                GroupForm::query()->updateOrCreate(
                    ['code' => $codeGroepsvorm],
                    [
                        'name' => $naamGroepsvorm,
                        'custom' => false
                    ]
                );
            }

//            GroupForm::query()->updateOrCreate([
//                'name' => 'Individueel',
//            ], [
//                'code' => 'GV01',
//                'custom' => false
//            ]);
//            GroupForm::query()->updateOrCreate([
//                'name' => 'Groep',
//            ], [
//                'code' => 'GV02',
//                'custom' => false
//            ]);
        });
    }
}
