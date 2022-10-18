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

    public static function export($importMark)
    {
        $import = new static($importMark);
        $import->handle();
    }

    abstract public function handle();

    protected function createExportJson(Collection $dataCollection)
    {
        $json = json_encode($dataCollection, JSON_PRETTY_PRINT);
        $filePath = static::getFilePath();
        StorageService::getStorage()
            ->put($filePath, $json);
    }

    protected function getFilePath(): string
    {
        $filename = $this->importMark . '-' . $this->entity;
        $filename = $this->dateMark ? date('dmy') . '-' . $filename : $filename;
        return $this->getDirectory() . $filename.'.json';
    }

    protected function getDirectory(): string
    {
        return 'exports/';
    }
}