<?php

namespace Vng\EvaCore\Services\Export;

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