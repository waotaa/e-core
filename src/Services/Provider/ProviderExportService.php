<?php

namespace Vng\EvaCore\Services\Provider;

use Vng\EvaCore\ElasticResources\ProviderResource;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class ProviderExportService extends AbstractEntityExportService
{
    protected string $entity = 'provider';

    public function handle(): string
    {
        $providers = Provider::all()
            ->map(function(Provider $provider) {
                $provider->import_mark = $this->importMark;
                return ProviderResource::make($provider)->toArray();
            });
        return $this->createExportJson($providers);
    }
}
