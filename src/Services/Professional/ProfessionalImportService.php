<?php

namespace Vng\EvaCore\Services\Professional;

use Vng\EvaCore\Services\ImExport\AbstractEntityImportService;
use Vng\EvaCore\Services\ImExport\ProfessionalFromArrayService;

class ProfessionalImportService extends AbstractEntityImportService
{
    protected string $entity = 'professional';

    public function handle()
    {
        $professionals = $this->getDataFromFile();
        foreach ($professionals as $professional) {
            ProfessionalFromArrayService::create($professional);
        }
    }
}