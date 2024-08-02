<?php

namespace Vng\EvaCore\Services\ImExport;

use Vng\EvaCore\Services\StorageService;

abstract class AbstractEntityExportService
{
    protected string $type;
    protected ?string $exportMark;
    protected bool $dateMark = false;

    // todo: replace exportMark with Export entity
    public function __construct($mark = null)
    {
        $this->exportMark = $mark;
    }

    public static function make($exportMark = null): self
    {
        return new static($exportMark);
    }

    public static function export($exportMark = null)
    {
        $service = static::make($exportMark);
        return $service->handle();
    }

    public function dateMarkFileName(): self
    {
        $this->dateMark = true;
        return $this;
    }

    abstract public function handle();

    protected function storeExportJson(string $json): string
    {
        $filePath = static::getFilePath();
        StorageService::getStorage()
            ->put($filePath, $json);
        return $filePath;
    }

    protected function getFilePath(): string
    {
        return $this->getDirectory() . $this->getFileName();
    }

    public function getFileName()
    {
        $filename = $this->type;
        $filename = !is_null($this->exportMark) ? $this->exportMark . '-' . $filename : $filename;
        $filename = $this->dateMark ? date('dmy') . '-' . $filename : $filename;
        return $filename.'.json';
    }

    protected function getDirectory(): string
    {
        return 'exports/';
    }
}