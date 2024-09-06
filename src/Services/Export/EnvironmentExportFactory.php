<?php

namespace Vng\EvaCore\Services\Export;

use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;
use function app;

class EnvironmentExportFactory
{
    public static function create(Export $export)
    {
        Log::info('Exp.Environment Creating environment export');

        /** @var Organisation $organisation */
        $organisation = $export->organisation;

        /** @var EnvironmentRepositoryInterface $environmentRepo */
        $environmentRepo = app(EnvironmentRepositoryInterface::class);

        $query = $environmentRepo->getElasticResourceBuilder();
        $query = $environmentRepo->addOrganisationCondition($query, $organisation);
        $environments = $query->cursor();

        $exportService = ProviderExportService::make($export);
        $exportService->setItems($environments);
        $exportService->handle();
    }
}
