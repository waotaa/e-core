<?php

namespace Vng\EvaCore\Jobs\Export;

use Vng\EvaCore\Services\Storage\TempLocalStorageService;

trait useTempLocalStorageTrait
{

    public function getDirectory($mark): string
    {
        return "exports/{$mark}";
    }

    public function storeFile($file, $mark, $filename): ?string
    {
        $path = $this->getDirectory($mark) . "/{$filename}";
        $storageService = TempLocalStorageService::make();
        return $storageService->storeFile($file, $path);
    }

    public function getAllFiles($mark): array
    {
        $directory = $this->getDirectory($mark);
        $storageService = TempLocalStorageService::make();
        return $storageService->files($directory);
    }
}