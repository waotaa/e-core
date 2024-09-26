<?php

namespace Vng\EvaCore\Services\Storage;

class TempLocalStorageService extends AbstractStorageService
{
    public function getBasePath(): string
    {
        return 'temp';
    }

    public function getStorageDiskName(): string
    {
        return config('filesystems.temp', 'temp');
    }
}
