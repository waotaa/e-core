<?php

namespace Vng\EvaCore\Services\Environment;

use Vng\EvaCore\ElasticResources\EnvironmentResource;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class EnvironmentExportService extends AbstractEntityExportService
{
    protected string $type = 'environment';

    public function getExportArray(): array
    {
        return Environment::all()
            ->map(function(Environment $environment) {
                return EnvironmentResource::make($environment)->toArray();
            })
            ->toArray();
    }

    public function handle(): string
    {
        $json = json_encode($this->getExportArray(), JSON_PRETTY_PRINT);
        return $this->storeExportJson($json);
    }
}
