<?php

namespace Vng\EvaCore\Services\Environment;

use Vng\EvaCore\Services\ImExport\AbstractEntityImportService;
use Vng\EvaCore\Services\ImExport\EnvironmentFromArrayService;

class EnvironmentImportService extends AbstractEntityImportService
{
    protected string $entity = 'environment';

    public function handle()
    {
        $environments = $this->getDataFromFile();
        foreach ($environments as $environment) {
            EnvironmentFromArrayService::create($environment);
        }
    }
}