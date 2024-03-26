<?php

namespace Vng\EvaCore\Services\Storage;

use JetBrains\PhpStorm\Pure;
use League\Flysystem\Util;
use Vng\EvaCore\Models\Organisation;

abstract class AbstractOrganisationStorageService extends AbstractStorageService
{
    protected $visibility = 'private';
    protected ?Organisation $organisation = null;

    public function setOrganisation(Organisation $organisation): self
    {
        $this->organisation = $organisation;
        return $this;
    }

    protected function ensureOrganisationIsSet(): void
    {
        if (!$this->organisation) {
            throw new \Exception("Organisation must be set.");
        }
    }

    #[Pure]
    protected function getOrganisationPathPrefix(Organisation $organisation): string
    {
        return $organisation->id .  '-' . $organisation->getSlugAttribute();
    }

    protected function getStorageDirectory(): string
    {
        $this->ensureOrganisationIsSet();
        $storageDir = parent::getStorageDirectory();
        $storageDir .= $this->getOrganisationPathPrefix($this->organisation);
        return Util::normalizePath($storageDir);
    }
}