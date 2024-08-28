<?php

namespace Vng\EvaCore\Services\Export\Base;

use Illuminate\Support\Facades\App;
use Vng\EvaCore\Models\Export;

abstract class AbstractEntityExportService
{
    protected string $type;
    protected ?string $exportMark;
    protected bool $dateMark = false;
    protected Export $export;

    public function __construct(Export $export)
    {
        $this->setExport($export);
    }

    public static function make(Export $export): self
    {
        return new static($export);
    }

    public function setExport(Export $export): static
    {
        $this->export = $export;
        return $this;
    }

    protected function getMark(): string
    {
        $organisation = $this->export->organisation;
        $mark = "{$organisation->id}-{$this->type}-".date('dmyhis');
        return $mark;
    }

    public static function export(Export $export)
    {
        $service = static::make($export);
        return $service->handle();
    }

    public function dateMarkFileName(): self
    {
        $this->dateMark = true;
        return $this;
    }

    abstract public function handle();

    protected function startExport(): static
    {
//        For local testing when no QUEUE is set up
        if (App::environment('local')) {
            ini_set('memory_limit', '2G');
            set_time_limit(3000);
        }
        return $this;
    }

    protected function updateExportStatusToInitiated(): static
    {
        $this->export->fill([
            'type' => $this->type,
            'mark' => $this->getMark(),
            'status' => Export::STATUS_INITIATED
        ])->saveQuietly();
        return $this;
    }

    protected function updateExportStatusToFailed(): static
    {
        $this->export->fill([
            'status' => Export::STATUS_FAILED
        ])->saveQuietly();
        return $this;
    }

    protected function updateExportStatusToFinished(): static
    {
        $this->export->fill([
            'status' => Export::STATUS_DONE
        ])->saveQuietly();
        return $this;
    }

//    protected function storeExportJson(string $json): string
//    {
//        $filePath = static::getFilePath();
//        StorageService::getStorage()
//            ->put($filePath, $json);
//        return $filePath;
//    }
//
//    protected function getFilePath(): string
//    {
//        return $this->getDirectory() . $this->getFileName();
//    }
//
//    public function getFileName()
//    {
//        $filename = $this->type;
//        $filename = !is_null($this->exportMark) ? $this->exportMark . '-' . $filename : $filename;
//        $filename = $this->dateMark ? date('dmy') . '-' . $filename : $filename;
//        return $filename.'.json';
//    }
//
//    protected function getDirectory(): string
//    {
//        return 'exports/';
//    }
}