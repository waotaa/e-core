<?php

namespace Vng\EvaCore\Services\Storage;

use JetBrains\PhpStorm\Pure;
use League\Flysystem\Util;
use Vng\EvaCore\Models\Release;
use function config;

class ReleaseImageStorageService extends AbstractStorageService
{
    protected $visibility = 'public';

    protected ?Release $release = null;

    public function getBasePath(): string
    {
        return config('filesystems.storage_paths.releases', 'releases');
    }

    public function setRelease(Release $release): self
    {
        $this->release = $release;
        return $this;
    }

    protected function ensureReleaseIsSet(): void
    {
        if (!$this->release) {
            throw new \Exception("Release must be set.");
        }
    }

    #[Pure]
    protected function getReleasePathPrefix(Release $release): string
    {
        if ($release->version) {
            return $release->version;
        }
        return 'id-' . $release->id;
    }

    protected function getStorageDirectory(): string
    {
        $this->ensureReleaseIsSet();
        $storageDir = parent::getStorageDirectory();
        $storageDir .= $this->getReleasePathPrefix($this->release);
        return Util::normalizePath($storageDir);
    }
}