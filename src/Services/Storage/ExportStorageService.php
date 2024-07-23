<?php

namespace Vng\EvaCore\Services\Storage;

use function config;

class ExportStorageService extends AbstractOrganisationStorageService
{
    public function getBasePath(): string
    {
        return config('filesystems.storage_paths.exports', 'exports');
    }
}
