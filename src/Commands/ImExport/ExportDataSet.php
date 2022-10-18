<?php

namespace Vng\EvaCore\Commands\ImExport;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\ImExport\ExportService;

class ExportDataSet extends Command
{
    protected $signature = 'export:set {import-mark}';

    protected $description = 'Import dataset from export json';

    public function handle(): int
    {
        ExportService::export($this->argument('import-mark'));
        return 0;
    }
}