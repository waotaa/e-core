<?php

namespace Vng\EvaCore\Services\Instrument;

use Vng\EvaCore\ElasticResources\InstrumentResource;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class InstrumentExportService extends AbstractEntityExportService
{
    protected string $entity = 'instrument';

    public function handle(): string
    {
        $instruments = Instrument::all()
            ->map(function(Instrument $instrument) {
                $instrument->import_mark = $this->importMark;
                return InstrumentResource::make($instrument)->toArray();
            });
        return $this->createExportJson($instruments);
    }
}
