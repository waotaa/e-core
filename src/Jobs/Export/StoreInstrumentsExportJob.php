<?php

namespace Vng\EvaCore\Jobs\Export;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Services\Storage\ExportStorageService;
use Vng\EvaCore\Services\Storage\TempLocalStorageService;

class StoreInstrumentsExportJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        useExportEntityTrait,
        useTempLocalStorageTrait;

    public function __construct(
        protected int $exportId
    ) {}

    public function handle(): void
    {
        $this->findExport($this->exportId);
        Log::info('Exp.Instruments StoreInstrumentsExportJob started');

        $mark = $this->export->getAttribute('mark');

        Log::debug("Exp.Instruments Storing export for {$mark}");
        $wrappedFile = $this->getDirectory($mark) . "/wrapped.json";

        $storageService = TempLocalStorageService::make();
        $storageDisk = $storageService->getStorageDisk();
        $storageDir = $storageService->getStorageDirectory();
        $wrappedFile = Str::finish($storageDir, '/') . $wrappedFile;

        $diskName = $storageService->getStorageDiskName();
        Log::debug("Exp.Instruments Looking for wrapped file at disk {$diskName} path {$wrappedFile}");

        $contents = $storageDisk->get($wrappedFile);

        $exportStorageService = ExportStorageService::make();
        if (!is_null($this->export->organisation)) {
            $exportStorageService->setOrganisation($this->export->organisation);
        }
        $filePath = $exportStorageService->storeFile($contents, "{$mark}.json");
        if (is_null($filePath)) {
            $this->export->fill([
                'status' => Export::STATUS_FAILED
            ]);
        }
        $storageDisk->delete($wrappedFile);

        $this->export->fill([
            'file' => $filePath
        ])->saveQuietly();
    }

    public function failed(Throwable $exception)
    {
        $this->findExport($this->exportId);
        $this->export->fill([
            'status' => Export::STATUS_FAILED
        ])->saveQuietly();
    }
}
