<?php

namespace Vng\EvaCore\Commands\Export;

use Illuminate\Console\Command;
use Vng\EvaCore\Services\Export\ExportService;

class ExportDataSet extends Command
{
    protected $signature = 'export:set {import-mark}';

    protected $description = 'Export dataset with import mark';

    public function handle(): int
    {
        ExportService::export($this->argument('import-mark'));
        return 0;
    }
}