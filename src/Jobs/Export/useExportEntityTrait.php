<?php

namespace Vng\EvaCore\Jobs\Export;

use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Repositories\ExportRepositoryInterface;

trait useExportEntityTrait
{
    protected ?Export $export;

    public function findExport($exportId): Export
    {
        /** @var ExportRepositoryInterface $exportRepo */
        $exportRepo = app(ExportRepositoryInterface::class);
        $this->export = $exportRepo->find($exportId);
        return $this->export;
    }

    public function getExport(): Export
    {
        return $this->export;
    }
}