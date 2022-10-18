<?php

namespace Vng\EvaCore\Services\ImExport;

use Vng\EvaCore\Services\Environment\EnvironmentImportService;
use Vng\EvaCore\Services\Instrument\InstrumentImportService;
use Vng\EvaCore\Services\Professional\ProfessionalImportService;
use Vng\EvaCore\Services\Provider\ProviderImportService;

class ImportService
{
    public static function import($filenameBase)
    {
//        EnvironmentImportService::import($filenameBase);
//        ProfessionalImportService::import($filenameBase);
        InstrumentImportService::import($filenameBase);
        ProviderImportService::import($filenameBase);
    }
}