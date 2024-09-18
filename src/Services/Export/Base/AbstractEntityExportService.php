<?php

namespace Vng\EvaCore\Services\Export\Base;

use Illuminate\Support\Facades\App;
use Vng\EvaCore\Enums\ExportStatusEnum;
use Vng\EvaCore\Models\Export;

abstract class AbstractEntityExportService
{
    protected string $type;
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

        $this->export->fill([
            'type' => $this->type,
            'mark' => $this->getMark(),
        ])->saveQuietly();

        return $this;
    }

    protected static function updateExportStatusToInitiated(Export $export)
    {
        $export->fill([
            'status' => ExportStatusEnum::initiated()->getKey()
        ])->saveQuietly();
    }

    protected static function updateExportStatusToInProgress(Export $export)
    {
        $export->fill([
            'status' => ExportStatusEnum::inProgress()->getKey()
        ])->saveQuietly();
    }

    protected static function updateExportStatusToFailed(Export $export)
    {
        $export->fill([
            'status' => ExportStatusEnum::failed()->getKey()
        ])->saveQuietly();
    }

    protected static function updateExportStatusToDone(Export $export)
    {
        $export->fill([
            'status' => ExportStatusEnum::done()->getKey()
        ])->saveQuietly();
    }
}