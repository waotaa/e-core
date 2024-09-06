<?php

namespace Vng\EvaCore\Services\Export;

use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use function app;

class InstrumentExportFactory
{
    public static function create(Export $export)
    {
        Log::info('Exp.Instruments Creating instrument export');

        /** @var Organisation $organisation */
        $organisation = $export->organisation;

        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);

        $query = $instrumentRepo->getElasticResourceBuilder();
        $query = $instrumentRepo->addOrganisationCondition($query, $organisation);
        $instruments = $query->cursor();

        $exportService = InstrumentExportService::make($export);
        $exportService->setItems($instruments);
        $exportService->handle();
    }
}
