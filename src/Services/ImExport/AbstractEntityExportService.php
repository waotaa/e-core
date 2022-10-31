<?php

namespace Vng\EvaCore\Services\ImExport;

use Vng\EvaCore\Services\StorageService;
use Illuminate\Support\Collection;

abstract class AbstractEntityExportService
{
    protected string $entity;
    protected ?string $importMark;
    protected bool $dateMark = false;

    public function __construct($importMark = null)
    {
        $this->importMark = $importMark;
    }

    public static function make($importMark = null): self
    {
        return new static($importMark);
    }

    public static function export($importMark = null)
    {
        $service = static::make($importMark);
        return $service->handle();
    }

    abstract public function handle(): string;

    public function dateMarkFileName(): self
    {
        $this->dateMark = true;
        return $this;
    }

    protected function createExportJson(Collection $dataCollection): string
    {
        $json = json_encode($dataCollection, JSON_PRETTY_PRINT);
        $filePath = static::getFilePath();
        StorageService::getStorage()
            ->put($filePath, $json);
        return $filePath;
    }

    protected function getFilePath(): string
    {
        $filename = $this->entity;
        $filename = !is_null($this->importMark) ? $this->importMark . '-' . $filename : $filename;
        $filename = $this->dateMark ? date('dmy') . '-' . $filename : $filename;
        return $this->getDirectory() . $filename.'.json';
    }

    protected function getDirectory(): string
    {
        return 'exports/';
    }
}