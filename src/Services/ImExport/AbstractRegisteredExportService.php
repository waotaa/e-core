<?php

namespace Vng\EvaCore\Services\ImExport;

use Exception;
use Illuminate\Support\Facades\App;
use Vng\EvaCore\Models\Export;

/**
 * This class is an extension of the default AbstractEntityExportService
 * This class adds the functionality of registering the export progress in the Export entity
 */
abstract class AbstractRegisteredExportService extends AbstractEntityExportService
{
    protected ?Export $export = null;

    public function setExport(Export $export): static
    {
        $this->export = $export;
        return $this;
    }

    protected function getMark(): string
    {
        $organisation = $this->export->organisation;
        $mark = "{$organisation->id}-{$this->type}-".date('dmyhis');
        $this->exportMark = $mark;
        return $mark;
    }

    protected function startExport(): static
    {
        if (is_null($this->export)) {
            throw new Exception("Export property is required.");
        }

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
}