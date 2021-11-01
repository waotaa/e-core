<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\ElasticResources\Instrument\InstrumentCostResource;
use Vng\EvaCore\Models\Instrument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportInstrumentsCosts extends Command
{
    protected $signature = 'eva:export-instruments-costs {mark?}';
    protected $description = 'Create a json file with all instrument costs data.';

    public function handle(): int
    {
        $this->output->writeln('exporting instruments');

        $instruments = Instrument::all();
        $instrumentResources = collect($instruments)->map(function(Instrument $instrument) {
            return InstrumentCostResource::make($instrument)->toArray();
        });

        $instrumentsJson = json_encode($instrumentResources, JSON_PRETTY_PRINT);

        $name_prefix = date('dmy');
        $filename = $name_prefix . '-instruments-costs';
        if ($this->hasArgument('mark') && !empty($this->argument('mark'))) {
            $filename = $name_prefix . '-' . $this->argument('mark') . '-instruments-costs';
        }

        Storage::disk('local')->put("exports/{$filename}.json", $instrumentsJson);

        $this->output->writeln('finished');
        return 0;
    }
}
