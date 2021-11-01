<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Models\Area;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Illuminate\Console\Command;

class MakeAreas extends Command
{
    protected $signature = 'eva:make-areas';
    protected $description = 'Make an area for every Region / Township that needs one';

    public function handle(): int
    {
        $this->getOutput()->writeln('making areas...');
        $this->output->writeln('');

        $createdAreas = [];

        $regions = Region::all();
        $regions->each(function(Region $region) use ($createdAreas) {
            $area = Area::query()->firstOrCreate([
                'area_id' => $region->id,
                'area_type' => Region::class
            ]);
            array_push($createdAreas, $area);
        });

        $townships = Township::all();
        $townships->each(function(Township $township) use ($createdAreas) {
            $area = Area::query()->firstOrCreate([
                'area_id' => $township->id,
                'area_type' => Township::class
            ]);
            array_push($createdAreas, $area);
        });

        $createdAreaCount = count($createdAreas);
        if ($createdAreaCount > 0) {
            $this->output->writeln($createdAreaCount . ' areas created');
            foreach ($createdAreas as $area) {
                $this->output->writeln(' -'.$area->area->name);
            }
            $this->output->writeln('');
        } else {
            $this->output->note('no area creation needed');
        }

        $this->getOutput()->writeln('making areas finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
