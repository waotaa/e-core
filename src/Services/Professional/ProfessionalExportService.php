<?php

namespace Vng\EvaCore\Services\Professional;

use Vng\EvaCore\Http\Resources\ProfessionalResource;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class ProfessionalExportService extends AbstractEntityExportService
{
    protected string $entity = 'professional';

    public function handle(): string
    {
        $professionals = Professional::query()->with('environment')->get()
            ->map(function(Professional $professional) {
                return ProfessionalResource::make($professional)->jsonSerialize();
//                return ProfessionalResource::make($professional)->toArray();
            });
        return $this->createExportJson($professionals);
    }
}
