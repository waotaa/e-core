<?php

namespace Vng\EvaCore\Services\Export;

use Vng\EvaCore\ElasticResources\ProviderResource;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Services\Export\Base\AbstractEntityExportService;

class ProviderExportService extends AbstractEntityExportService
{
    protected string $type = 'provider';

    public function getExportArray(): array
    {
        return Provider::all()
            ->map(function(Provider $provider) {
                $provider->import_mark = $this->exportMark;
                return ProviderResource::make($provider)->toArray();
            })
            ->toArray();
    }

    public function handle(): string
    {
        $json = json_encode($this->getExportArray(), JSON_PRETTY_PRINT);
        return $this->storeExportJson($json);
    }
}
