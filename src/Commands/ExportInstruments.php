<?php

namespace Vng\EvaCore\Commands;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\Instrument\InstrumentExportService;

class ExportInstruments extends Command
{
    protected $signature = 'eva:export-instruments {mark?}';
    protected $description = 'Create a json file with all instrument data. This json can also be used for an import';

    public function handle(): int
    {
        $this->output->writeln('exporting instruments');

        InstrumentExportService::exportAllInstruments($this->argument('mark'));

        $this->output->writeln('finished');
        return 0;
    }
}
