<?php

namespace Vng\EvaCore\Services\Professional;

use Vng\EvaCore\ElasticResources\ProfessionalResource;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class ProfessionalExportService extends AbstractEntityExportService
{
    protected string $entity = 'professional';

    public function handle()
    {
        $professionals = Professional::all()
            ->map(function(Professional $professional) {
                return ProfessionalResource::make($professional)->toArray();
            });
        $this->createExportJson($professionals);
    }
}
