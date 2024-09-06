<?php

namespace Vng\EvaCore\Jobs\Export\Instrument;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vng\EvaCore\Jobs\Export\useExportEntityTrait;
use Vng\EvaCore\Jobs\Export\useMemoryUsageTrait;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\Export\InstrumentExportService;
use function app;

class ExportInstrumentsJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        useExportEntityTrait,
        useMemoryUsageTrait;

    public function __construct(
        protected int $exportId
    ) {}

    protected ?Instrument $instrument;

    public function handle(): void
    {
        $export = $this->findExport($this->exportId);
        Log::info('Exp.Instruments ExportInstrumentsJob started');

        /** @var Organisation $organisation */
        $organisation = $export->organisation;

        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);

        $query = $instrumentRepo->getElasticResourceBuilder();
        $query = $instrumentRepo->addOrganisationCondition($query, $organisation);
        $instruments = $query->cursor();

        $exportService = InstrumentExportService::make($export);
        $exportService->setItems($instruments);

        $this->logMemoryUsage();
        $exportService->handle();
        $this->logMemoryUsage();
    }
}
