<?php

namespace Vng\EvaCore\Services\Instrument;

use Illuminate\Bus\Batch;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;
use Vng\EvaCore\Jobs\Export\ProcessInstrumentJob;
use Vng\EvaCore\Jobs\Export\StoreInstrumentsExportJob;
use Vng\EvaCore\Jobs\Export\WrapInstrumentsJob;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\ImExport\AbstractRegisteredExportService;

class InstrumentExportService extends AbstractRegisteredExportService
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
            $jobs
        ])
            ->then(function (Batch $batch) {
                WrapInstrumentsJob::dispatch($this->export)
                    ->chain([
                        StoreInstrumentsExportJob::dispatch($this->export),
                    ])
                    ->then(function () {
                        Log::info('Instrument export done');
                        $this->updateExportStatusToFinished();
                    });
            })
            ->catch(function (Batch $batch, Throwable $e) {
                Log::error('Instrument export failed');
            })
            ->name($this->export->getAttribute('mark'))
            ->dispatch();
    }
}
