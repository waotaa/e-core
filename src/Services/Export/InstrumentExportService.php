<?php

namespace Vng\EvaCore\Services\Export;

use Illuminate\Bus\Batch;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;
use Vng\EvaCore\Jobs\Export\Instrument\ProcessInstrumentJob;
use Vng\EvaCore\Jobs\Export\Instrument\StoreInstrumentsExportJob;
use Vng\EvaCore\Jobs\Export\Instrument\WrapInstrumentsJob;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\Export\Base\AbstractEntityExportService;
use function app;

class InstrumentExportService extends AbstractEntityExportService
{
    protected string $type = Export::TYPE_INSTRUMENT;

    protected ?Enumerable $items = null;

    public function setItems(Enumerable $items): static
    {
        $this->items = $items;
        return $this;
    }

    public function setDefaultItems(): static
    {
        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $this->setItems($instrumentRepo->getElasticResourceBuilder()->get());
        return $this;
    }

    /**
     * @throws Throwable
     */
    public function handle()
    {
        $this->startExport();
        $this->updateExportStatusToInitiated();

        if (is_null($this->items)) {
            $this->setDefaultItems();
        }

        $jobs = [];
        foreach ($this->items as $instrument) {
            $jobs[] = new ProcessInstrumentJob($this->export, $instrument);
        }

        Bus::batch([
            $jobs,
            new WrapInstrumentsJob($this->export),
            new StoreInstrumentsExportJob($this->export)
        ])
            ->then(function (Batch $batch) {
                Log::info('Instrument export done');
                $this->updateExportStatusToFinished();
            })
            ->catch(function (Batch $batch, Throwable $e) {
                Log::error('Instrument export failed');
            })
            ->name($this->export->getAttribute('mark'))
            ->dispatch();
    }


}
