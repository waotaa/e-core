<?php

namespace Vng\EvaCore\Services\Instrument;

use Vng\EvaCore\Services\ImExport\AbstractEntityImportService;
use Vng\EvaCore\Services\ImExport\InstrumentFromArrayService;

class InstrumentImportService extends AbstractEntityImportService
{
    protected string $entity = 'instrument';

    public function handle()
    {
        $instruments = $this->getDataFromFile();
        foreach ($instruments as $instrument) {
            InstrumentFromArrayService::create($instrument);
        }
    }
}