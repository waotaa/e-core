<?php

namespace Vng\EvaCore\Services\Storage;

use function config;

class DownloadStorageService extends AbstractOrganisationStorageService
{
    public function getBasePath(): string
    {
        return config('filesystems.storage_paths.downloads', 'downloads');
    }
}
