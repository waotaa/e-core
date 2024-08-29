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
use Vng\EvaCore\Services\Storage\TempLocalStorageService;

class WrapInstrumentsJob implements ShouldQueue
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
        Log::info('Exp.Instruments WrapInstrumentsJob started');

        $mark = $this->export->getAttribute('mark');

        Log::debug("Exp.Instruments Wrapping for {$mark}");

        $exportPath = $this->getDirectory($mark) . "/wrapped.json";

        $storageService = TempLocalStorageService::make();
        $storageDisk = $storageService->getStorageDisk();
        $storageDir = $storageService->getStorageDirectory();
        $exportPath = Str::finish($storageDir, '/') . $exportPath;

        Log::debug("Exp.Instruments At path {$exportPath}");

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
    }

    public function failed(Throwable $exception)
    {
        $this->export->fill([
            'status' => Export::STATUS_FAILED
        ])->saveQuietly();
    }
}
