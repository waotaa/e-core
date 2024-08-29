<?php

namespace Vng\EvaCore\Jobs\Export;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\Instrument\InstrumentExportService;
use function app;

class ExportInstrumentsJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        useExportEntityTrait;

    public function __construct(
        protected int $exportId
    ) {}

    protected ?Instrument $instrument;

    public function handle(): void
    {
        $this->findExport($this->exportId);
        Log::info('Exp.Instruments ExportInstrumentsJob started');

        /** @var Organisation $organisation */
        $organisation = $this->export->organisation;

        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);

        $query = $instrumentRepo->getElasticResourceBuilder();
        $query = $instrumentRepo->addOrganisationCondition($query, $organisation);
        $instruments = $query->cursor();

        $exportService = InstrumentExportService::make();
        $exportService->setExport($this->export);
        $exportService->setItems($instruments);

        Log::info("Memory usage 1: " . $this->formatBytes(memory_get_usage()));
        $exportService->handle();
        Log::info("Memory usage 2: " . $this->formatBytes(memory_get_usage()));

    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
