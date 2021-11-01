<?php

namespace Vng\EvaCore\Commands\Geo;

use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Illuminate\Console\Command;

class RegionsAssign extends Command
{
    protected $signature = 'geo:regions-assign';
    protected $description = 'Assign a region to every township';

    public function handle(): int
    {
        $this->getOutput()->writeln('assigning regions');
        $this->output->writeln('');

        $regions = Region::all();
        foreach ($regions as $region) {
            $regionTownships = Township::query()->where('region_code', $region->code)->get();
            $region->townships()->saveMany($regionTownships);
        }

        $regionLessTownships = Township::query()->whereNull('region_id')->get();

        if($regionLessTownships->count() > 0) {
            $this->output->warning('Townships without region found [' . $regionLessTownships->count() . ']');
            foreach ($regionLessTownships as $township) {
                $this->output->writeln('* ' . $township->name);
            }
        }

        $this->getOutput()->writeln('assigning regions finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }
}
