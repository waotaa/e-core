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
        Log::info("Exp.Instruments InstrumentExportService started");
        $this->startExport();
        $this->updateExportStatusToInProgress();

        if (is_null($this->items)) {
            $this->setDefaultItems();
        }

        if (empty($this->items)) {
            $this->updateExportStatusToFailed();
            Log::warning("Exp.Instruments Export items is empty");
            return;
        }

        $jobs = [];
        foreach ($this->items as $instrument) {
            $jobs[] = new ProcessInstrumentJob(
                $this->export->id,
//                $instrument->id
            );
        }

        $batchName = $this->export->getAttribute('mark');
        $export = $this->export;
        Bus::batch([
            $jobs
        ])
            ->name($batchName)
//            ->then(function (Batch $batch) use ($batchName, $export) {
//                $progress = $batch->progress();
//                Log::info("Exp.Instruments Batch {$batchName} done - progress: $progress");
//                $export->fill([
//                    'progress' => $batch->progress()
//                ])->saveQuietly();

//                Bus::chain([
//                    new WrapInstrumentsJob($export->id),
//                    new StoreInstrumentsExportJob($export->id),
//                    function() {
////                        Log::info('Instrument export done');
////                        $this->updateExportStatusToFinished();
//                    }
//                ])->dispatch();
//            })
            ->catch(function (Batch $batch, Throwable $e) {
//                Log::error('Instrument export failed');
//                $this->updateExportStatusToFailed();
            })
//            ->onQueue('exports')
            ->dispatch();
    }
}
