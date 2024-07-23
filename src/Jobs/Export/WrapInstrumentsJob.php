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
use Vng\EvaCore\Services\Storage\TempLocalStorageService;

class WrapInstrumentsJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        Batchable,
        SerializesModels,
        useTempLocalStorageTrait;

    public function __construct(
        protected string $mark,
    )
    {}

    public function handle(): void
    {
        Log::info("Wrapping for {$this->mark}");

        $exportPath = $this->getDirectory($this->mark) . "/wrapped.json";

        $storageService = TempLocalStorageService::make();
        $storageDisk = $storageService->getStorageDisk();
        $storageDir = $storageService->getStorageDirectory();
        $exportPath = Str::finish($storageDir, '/') . $exportPath;

        Log::debug("At path {$exportPath}");


        $files = $this->getAllFiles($this->mark);
        $storageDisk->put($exportPath, '[');

        $first = true;
        foreach ($files as $file) {
            if (!$first) {
                $storageDisk->append($exportPath, ',');
            }
            $contents = $storageDisk->get($file);
            $storageDisk->append($exportPath, $contents);
            $first = false;
        }

        $storageDisk->append($exportPath, ']');
        $storageDisk->delete($files);
    }
}
