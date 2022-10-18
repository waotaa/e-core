<?php

namespace Vng\EvaCore\Services\Environment;

use Vng\EvaCore\ElasticResources\EnvironmentResource;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class EnvironmentExportService extends AbstractEntityExportService
{
    protected string $entity = 'environment';

    public function handle()
    {
        $environments = Environment::all()
            ->map(function(Environment $environment) {
                return EnvironmentResource::make($environment)->toArray();
            });
        $this->createExportJson($environments);
    }
}
