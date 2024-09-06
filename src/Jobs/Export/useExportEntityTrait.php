<?php

namespace Vng\EvaCore\Jobs\Export;

use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Repositories\ExportRepositoryInterface;

trait useExportEntityTrait
{
    public function findExport($exportId): Export
    {
        /** @var ExportRepositoryInterface $exportRepo */
        $exportRepo = app(ExportRepositoryInterface::class);
        /** @var Export $export */
        $export = $exportRepo->find($exportId);
        return $export;
    }
}