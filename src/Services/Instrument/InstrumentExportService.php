<?php

namespace Vng\EvaCore\Services\Instrument;

use Illuminate\Bus\Batch;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;
use Vng\EvaCore\Jobs\Export\ProcessInstrumentJob;
use Vng\EvaCore\Jobs\Export\StoreInstrumentsExportJob;
use Vng\EvaCore\Jobs\Export\WrapInstrumentsJob;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class InstrumentExportService extends AbstractEntityExportService
{
    protected string $entity = 'instrument';

    protected ?Enumerable $items = null;
    protected ?Organisation $organisation = null;

    public function __construct($mark = null)
    {
        if (is_null($mark)) {
            throw new InvalidArgumentException("The mark parameter is required.");
        }
        parent::__construct($mark);
    }

    public function setDefaultItems(): static
    {
        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $this->setItems($instrumentRepo->getElasticResourceBuilder()->get());
        return $this;
    }

    public function setItems(Enumerable $items): static
    {
        $this->items = $items;
        return $this;
    }

    public function setOrganisation(Organisation $organisation): static
    {
        $this->organisation = $organisation;
        return $this;
    }

    public function handle()
    {
        if (is_null($this->items)) {
            $this->setDefaultItems();
        }

        // todo: export object meegeven aan jobs + progress updaten
        $jobs = [];
        foreach ($this->items as $instrument) {
            $jobs[] = new ProcessInstrumentJob($this->exportMark, $instrument);
        }

        Bus::batch([
            $jobs,
            new WrapInstrumentsJob($this->exportMark),
            new StoreInstrumentsExportJob($this->exportMark, $this->organisation)
        ])
            ->then(function (Batch $batch) {
                Log::info('Instrument export done');
            })
            ->catch(function (Batch $batch, Throwable $e) {
                Log::error('Instrument export failed');
            })
            ->name($this->exportMark)
            ->dispatch();
    }
}
