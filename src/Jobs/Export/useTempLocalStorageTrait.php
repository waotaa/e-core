<?php

namespace Vng\EvaCore\Jobs\Export;

use Vng\EvaCore\Services\Storage\TempLocalStorageService;

trait useTempLocalStorageTrait
{

    public function getTempDirectory($mark): string
    {
        return "exports/{$mark}";
    }

    public function storeTempFile($file, $mark, $filename): ?string
    {
        $path = $this->getTempDirectory($mark) . "/{$filename}";
        $storageService = TempLocalStorageService::make();
        return $storageService->storeFile($file, $path);
    }

    public function getAllTempFiles($mark): array
    {
        $directory = $this->getTempDirectory($mark);
        $storageService = TempLocalStorageService::make();
        return $storageService->files($directory);
    }
}