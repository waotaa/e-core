<?php

namespace Vng\EvaCore\Services\Export;

use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Export;

class ProfessionalExportFactory
{
    public static function create(Export $export, Environment $environment)
    {
        Log::info('Exp.Professionals Creating export');

        $professionals = $environment->professionals()->cursor();
        $exportService = ProfessionalExportService::make($export);
        $exportService->setItems($professionals);

        $exportService->handle();
    }
}
