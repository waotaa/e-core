<?php

namespace Vng\EvaCore\Services\Export;

use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Log;
use Throwable;
use Vng\EvaCore\ElasticResources\ProviderResource;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;
use Vng\EvaCore\Services\Export\Base\AbstractEntityExportService;
use Vng\EvaCore\Services\Storage\ExportStorageService;

class ProviderExportService extends AbstractEntityExportService
{
    protected string $type = Export::TYPE_PROVIDER;

    protected ?Enumerable $items = null;

    public function setItems(Enumerable $items): static
    {
        $this->items = $items;
        return $this;
    }

    public function setDefaultItems(): static
    {
        /** @var ProviderRepositoryInterface $providerRepo */
        $providerRepo = app(ProviderRepositoryInterface::class);
        $this->setItems($providerRepo->getElasticResourceBuilder()->get());
        return $this;
    }

    public function handle()
    {
        Log::info("Exp.Provider ExportService started");
        $this->startExport();
        self::updateExportStatusToInProgress($this->export);

        if (is_null($this->items)) {
            $this->setDefaultItems();
        }

        if (empty($this->items)) {
            self::updateExportStatusToFailed($this->export);
            Log::warning("Exp.Provider Export items is empty");
            return;
        }

        try {
            $data = $this->items->map(function (Provider $provider) {
                $provider->import_mark = $this->getMark();
                return ProviderResource::make($provider)->toArray();
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
