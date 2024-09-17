<?php

namespace Vng\EvaCore\Services\Storage;

use Illuminate\Support\Facades\App;

class TempLocalStorageService extends AbstractStorageService
{
    public function getBasePath(): string
    {
        return 'temp';
    }

    public function getStorageDiskName(): string
    {
        return config('filesystems.temp', 's3');
    }
}
