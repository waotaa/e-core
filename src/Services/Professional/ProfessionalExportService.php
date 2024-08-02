<?php

namespace Vng\EvaCore\Services\Professional;

use Vng\EvaCore\Http\Resources\ProfessionalResource;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class ProfessionalExportService extends AbstractEntityExportService
{
    protected string $type = 'professional';

    public function getExportArray(): array
    {
        return Professional::query()->with('environment')->get()
            ->map(function(Professional $professional) {
                return ProfessionalResource::make($professional)->jsonSerialize();
            })
            ->toArray();
    }

    public function handle(): string
    {
        $json = json_encode($this->getExportArray(), JSON_PRETTY_PRINT);
        return $this->storeExportJson($json);
    }
}
