<?php

namespace Vng\EvaCore\Services\ImExport;

use Vng\EvaCore\Services\StorageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

abstract class AbstractEntityImportService
{
    protected string $entity;

    protected ?string $filenameBase;

    public function __construct($filename)
    {
        $this->filenameBase = $filename;
    }

    public static function import($filenameBase)
    {
        $import = new static($filenameBase);
        $import->handle();
    }

    abstract public function handle();

    public function getDataFromFile()
    {
        try {
            $path = static::getFilePath();
            $contents = StorageService::getStorage()
                ->get($path);
        } catch (FileNotFoundException $e) {
            return null;
        }
        return json_decode($contents, true);
    }

    protected function getFilePath(): string
    {
        $filename = $this->filenameBase . '-' . $this->entity;
        return $this->getDirectory() . $filename.'.json';
    }

    protected function getDirectory(): string
    {
        return 'imports/';
    }
}