<?php

namespace Vng\EvaCore\Services\Storage;

class StoredFile
{
    public function __construct(
        public string $filename,
        public string $path
    ){}

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;
        return $this;
    }
}