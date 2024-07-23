<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Jobs\Export\ExportInstrumentsJob;
use Vng\EvaCore\Models\Export;

class ExportObserver
{
    public function created(Export $export): void
    {
        ExportInstrumentsJob::dispatch($export);
    }
}
