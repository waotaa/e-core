<?php

namespace Vng\EvaCore\Services\Storage;

use function config;

class LogoStorageService extends AbstractOrganisationStorageService
{
    protected $visibility = 'public';

    public function getBasePath(): string
    {
        return config('filesystems.storage_paths.logos', 'logos');
    }
}