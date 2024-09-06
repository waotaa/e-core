<?php

namespace Vng\EvaCore\Services\Export;

use Illuminate\Bus\Batch;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;
use Vng\EvaCore\Jobs\Export\Instrument\ProcessInstrumentJob;
use Vng\EvaCore\Jobs\Export\Instrument\StoreInstrumentsExportJob;
use Vng\EvaCore\Jobs\Export\Instrument\WrapInstrumentsJob;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\Export\Base\AbstractEntityExportService;
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
