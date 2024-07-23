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
use Vng\EvaCore\Models\Organisation;
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
        protected string $mark,
        protected ?Organisation $organisation = null
    )
    {}

    public function handle(): void
    {
        Log::info("Storing export for {$this->mark}");
        $wrappedFile = $this->getDirectory($this->mark) . "/wrapped.json";

        $storageService = TempLocalStorageService::make();
        $storageDisk = $storageService->getStorageDisk();
        $storageDir = $storageService->getStorageDirectory();
        $wrappedFile = Str::finish($storageDir, '/') . $wrappedFile;

        Log::debug("At path {$wrappedFile}");

        $contents = $storageDisk->get($wrappedFile);

        $exportStorageService = ExportStorageService::make();
        if (!is_null($this->organisation)) {
            $exportStorageService->setOrganisation($this->organisation);
        }
        $exportStorageService->storeFile($contents, "{$this->mark}.json");

        // todo: filename opslaan in export object

        $storageDisk->delete($wrappedFile);
    }
}
