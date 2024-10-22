<?php

namespace Vng\EvaCore\Services\Export;

use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Log;
use Throwable;
use Vng\EvaCore\ElasticResources\Original\ProfessionalResource;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;
use Vng\EvaCore\Services\Export\Base\AbstractEntityExportService;
use Vng\EvaCore\Services\Storage\ExportStorageService;

class ProfessionalExportService extends AbstractEntityExportService
{
    protected string $type = Export::TYPE_PROFESSIONAL;

    protected ?Enumerable $items = null;

    public function setItems(Enumerable $items): static
    {
        $this->items = $items;
        return $this;
    }

    public function setDefaultItems(): static
    {
        /** @var ProfessionalRepositoryInterface $professionalRepo */
        $professionalRepo = app(ProfessionalRepositoryInterface::class);
        $this->setItems($professionalRepo->getElasticResourceBuilder()->get());
        return $this;
    }

    public function handle()
    {
        Log::info("Exp.Professional ExportService started");
        $this->startExport();
        self::updateExportStatusToInProgress($this->export);

        if (is_null($this->items)) {
            $this->setDefaultItems();
        }

        if (empty($this->items)) {
            self::updateExportStatusToFailed($this->export);
            Log::warning("Exp.Professional Export items is empty");
            return;
        }

        try {
            $data = $this->items->map(function (Professional $professional) {
                return ProfessionalResource::make($professional)->toArray();
            })->toArray();
            $json = json_encode($data, JSON_PRETTY_PRINT);
            $this->storeFile($json);
            self::updateExportStatusToDone($this->export);
        } catch (Throwable $e) {
            $exportId = $this->export->id;
            Log::error("User export failed: {$exportId}");
            self::updateExportStatusToFailed($this->export);
            throw $e;
        }
    }

    protected function storeFile($contents)
    {
        $mark = $this->export->getAttribute('mark');
        Log::info("Storing export for {$mark}");

        $exportStorageService = ExportStorageService::make();
        if (!is_null($this->export->organisation)) {
            $exportStorageService->setOrganisation($this->export->organisation);
        }
        $filePath = $exportStorageService->storeFile($contents, "{$mark}.json");

        $this->export->fill([
            'file' => $filePath
        ])->saveQuietly();
    }
}
