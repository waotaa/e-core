<?php

namespace Vng\EvaCore\Services\Instrument;

use Illuminate\Support\Collection;
use Vng\EvaCore\ElasticResources\InstrumentResource;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class InstrumentExportService extends AbstractEntityExportService
{
    protected string $entity = 'instrument';

    protected ?Collection $items = null;

    public function handle(): string
    {
        if (is_null($this->items)) {
            $this->setItems(Instrument::all());
        }

        $instruments = $this->items->map(function(Instrument $instrument) {
            $instrument->import_mark = $this->importMark;
            return InstrumentResource::make($instrument)->toArray();
        });
        return $this->createExportJson($instruments);
    }

    /**
     * @param Collection|null $items
     * @return InstrumentExportService
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }
}
