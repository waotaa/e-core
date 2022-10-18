<?php

namespace Vng\EvaCore\Services\ImExport;

use Vng\EvaCore\Services\Environment\EnvironmentExportService;
use Vng\EvaCore\Services\Instrument\InstrumentExportService;
use Vng\EvaCore\Services\Professional\ProfessionalExportService;
use Vng\EvaCore\Services\Provider\ProviderExportService;

class ExportService
{
    public static function export($importMark)
    {
        EnvironmentExportService::export($importMark);
        ProfessionalExportService::export($importMark);
        ProviderExportService::export($importMark);
        InstrumentExportService::export($importMark);
    }
}