<?php

namespace Vng\EvaCore\Services\Provider;

use Vng\EvaCore\Services\ImExport\AbstractEntityImportService;
use Vng\EvaCore\Services\ImExport\ProviderFromArrayService;

class ProviderImportService extends AbstractEntityImportService
{
    protected string $entity = 'provider';

    public function handle()
    {
        $providers = $this->getDataFromFile();
        foreach ($providers as $provider) {
            ProviderFromArrayService::create($provider);
        }
    }
}