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
        if (App::environment('local')) {
            return 'local';
        }
        return config('filesystems.cloud', 's3');
    }
}
