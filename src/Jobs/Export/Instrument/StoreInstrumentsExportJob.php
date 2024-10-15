<?php

namespace Vng\EvaCore\Jobs\Export\Instrument;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Vng\EvaCore\Jobs\Export\useExportEntityTrait;
use Vng\EvaCore\Jobs\Export\useTempLocalStorageTrait;
use Throwable;
use Vng\EvaCore\Enums\ExportStatusEnum;
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
        $export = $this->findExport($this->exportId);
        Log::info('Exp.Instruments StoreInstrumentsExportJob started');

        $mark = $export->getAttribute('mark');

        Log::debug("Exp.Instruments Storing export for {$mark}");
        $wrappedFile = $this->getTempDirectory($mark) . "/wrapped.json";

        $storageService = TempLocalStorageService::make();
        $storageDisk = $storageService->getStorageDisk();
        $storageDir = $storageService->getStorageDirectory();
        $wrappedFile = Str::finish($storageDir, '/') . $wrappedFile;

        $diskName = $storageService->getStorageDiskName();
        Log::debug("Exp.Instruments Looking for wrapped file at disk {$diskName} path {$wrappedFile}");

        $contents = $storageDisk->get($wrappedFile);

        $exportStorageService = ExportStorageService::make();
        if (!is_null($export->organisation)) {
            $exportStorageService->setOrganisation($export->organisation);
        }
        $filePath = $exportStorageService->storeFile($contents, "{$mark}.json");
        if (is_null($filePath)) {
            Log::error("Exp.Instruments Storing file failed");
            $export->fill([
                'status' => ExportStatusEnum::failed()->getKey()
            ])->saveQuietly();
        }
        $storageDisk->delete($wrappedFile);

        $export->fill([
            'file' => $filePath
        ])->saveQuietly();
        Log::info('Exp.Instruments StoreInstrumentsExportJob finished');
    }

    public function failed(Throwable $exception)
    {
        Log::error('Exp.Instruments StoreInstrumentsExportJob failed');
        $export = $this->findExport($this->exportId);
        $export->fill([
            'status' => ExportStatusEnum::failed()->getKey()
        ])->saveQuietly();
    }
}
