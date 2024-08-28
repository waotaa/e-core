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
        protected Export $export,
    )
    {}

    public function handle(): void
    {
        $mark = $this->export->getAttribute('mark');

        Log::info("Wrapping for {$mark}");

        $exportPath = $this->getDirectory($mark) . "/wrapped.json";

        $storageService = TempLocalStorageService::make();
        $diskName = $storageService->getStorageDiskName();
        $storageDisk = $storageService->getStorageDisk();
        $storageDir = $storageService->getStorageDirectory();
        $exportPath = Str::finish($storageDir, '/') . $exportPath;

        Log::debug("On disk {$diskName}, At path {$exportPath}");

        $files = $this->getAllFiles($mark);
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

        $this->export->fill([
            'progress' => $this->batch()->progress()
        ])->saveQuietly();
    }
}
