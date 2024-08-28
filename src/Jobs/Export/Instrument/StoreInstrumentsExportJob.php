<?php

namespace Vng\EvaCore\Jobs\Export\Instrument;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Vng\EvaCore\Jobs\Export\useTempLocalStorageTrait;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Services\Storage\ExportStorageService;
use Vng\EvaCore\Services\Storage\TempLocalStorageService;

class StoreInstrumentsExportJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        Batchable,
        SerializesModels,
        useTempLocalStorageTrait;

    public function __construct(
        protected Export $export,
    )
    {}

    public function handle(): void
    {
        $mark = $this->export->getAttribute('mark');

        Log::info("Storing export for {$mark}");
        $wrappedFile = $this->getDirectory($mark) . "/wrapped.json";

        $storageService = TempLocalStorageService::make();
        $storageDisk = $storageService->getStorageDisk();
        $storageDir = $storageService->getStorageDirectory();
        $wrappedFile = Str::finish($storageDir, '/') . $wrappedFile;

        $diskName = $storageService->getStorageDiskName();
        Log::debug("Looking for wrapped file at disk {$diskName} path {$wrappedFile}");

        $contents = $storageDisk->get($wrappedFile);

        $exportStorageService = ExportStorageService::make();
        if (!is_null($this->export->organisation)) {
            $exportStorageService->setOrganisation($this->export->organisation);
        }
        $filePath = $exportStorageService->storeFile($contents, "{$mark}.json");
        $storageDisk->delete($wrappedFile);

        $this->export->fill([
            'progress' => $this->batch()->progress(),
            'file' => $filePath
        ])->saveQuietly();
    }
}
