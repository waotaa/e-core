<?php

namespace Vng\EvaCore\Jobs\Export\Instrument;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Vng\EvaCore\Jobs\Export\useMemoryUsageTrait;
use Vng\EvaCore\Models\Export;
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
        useMemoryUsageTrait;

    public function __construct(protected Export $export)
    {}

    public function handle(): void
    {
        /** @var Organisation $organisation */
        $organisation = $this->export->organisation;

        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);

        $query = $instrumentRepo->getElasticResourceBuilder();
        $query = $instrumentRepo->addOrganisationCondition($query, $organisation);
        $instruments = $query->cursor();

        $exportService = InstrumentExportService::make($this->export);
        $exportService->setItems($instruments);

        $this->logMemoryUsage();
        $exportService->handle();
        $this->logMemoryUsage();
    }
}
