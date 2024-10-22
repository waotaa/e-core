<?php

namespace Vng\EvaCore\Jobs\Export\Instrument;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;
use Vng\EvaCore\ElasticResources\Original\InstrumentResource;
use Vng\EvaCore\Enums\ExportStatusEnum;
use Vng\EvaCore\Jobs\Export\useExportEntityTrait;
use Vng\EvaCore\Jobs\Export\useTempLocalStorageTrait;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;

class ProcessInstrumentJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        Batchable,
        useExportEntityTrait,
        SerializesModels,
        useTempLocalStorageTrait;

    public function __construct(
        protected int $exportId,
        protected int $instrumentId
    ) {}

    public function handle(): void
    {
        $export = $this->findExport($this->exportId);
        Log::info('Exp.Instruments ProcessInstrumentJob started');

        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $instrument = $instrumentRepo->find($this->instrumentId);
        $mark = $export->getAttribute('mark');
        $instrument->import_mark = $mark;

        if ($this->batch()->cancelled()) {
            Log::warning("Exp.Instruments Skipped instrument {$instrument->id} for export {$mark}. Batch cancelled");
            return;
        }

        Log::debug("Exp.Instruments Processing instrument {$instrument->id} for export {$mark}");

        // Transform instrument to array
        $transformedItem = InstrumentResource::make($instrument)->toArray();
        $jsonItem = json_encode($transformedItem, JSON_PRETTY_PRINT);

        $result = $this->storeTempFile($jsonItem, $mark, "{$instrument->id}.json");

        Log::debug('done', [
            'success' => !is_null($result)
        ]);

        $export->fill([
            'progress' => $this->batch()->progress()
        ])->saveQuietly();
    }

    public function failed(Throwable $exception)
    {
        $export = $this->findExport($this->exportId);
        $export->fill([
            'status' => ExportStatusEnum::failed()->getKey()
        ])->saveQuietly();

        Log::error("ProcessInstrumentJob failed: export {$this->exportId}, instrument {$this->instrumentId}, error: {$exception->getMessage()}");
        Log::error($exception->getTraceAsString());
    }

}
