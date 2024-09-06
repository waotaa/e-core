<?php

namespace Vng\EvaCore\Services\Export;

use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;
use function app;

class ProviderExportFactory
{
    public static function create(Export $export)
    {
        Log::info('Exp.Provider Creating provider export');

        /** @var Organisation $organisation */
        $organisation = $export->organisation;

        /** @var ProviderRepositoryInterface $providerRepo */
        $providerRepo = app(ProviderRepositoryInterface::class);

        $query = $providerRepo->getElasticResourceBuilder();
        $query = $providerRepo->addOrganisationCondition($query, $organisation);
        $instruments = $query->cursor();

        $exportService = ProviderExportService::make($export);
        $exportService->setItems($instruments);
        $exportService->handle();
    }
}
