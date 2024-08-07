<?php

namespace Vng\EvaCore\Services\Instrument;

use Exception;
use Illuminate\Bus\Batch;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;
use Vng\EvaCore\Jobs\Export\ProcessInstrumentJob;
use Vng\EvaCore\Jobs\Export\StoreInstrumentsExportJob;
use Vng\EvaCore\Jobs\Export\WrapInstrumentsJob;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Illuminate\Support\Collection;
use Vng\EvaCore\ElasticResources\InstrumentWerknemersdienstverleningResource;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Services\ImExport\AbstractEntityExportService;

class InstrumentExportService extends AbstractEntityExportService
{
    protected string $type = Export::TYPE_INSTRUMENTS;

    protected ?Enumerable $items = null;
    protected ?Export $export = null;

    public function setExport(Export $export): static
    {
        $this->export = $export;
        return $this;
    }

    public function setItems(Enumerable $items): static
    {
        $this->items = $items;
        return $this;
    }

    public function setDefaultItems(): static
    {
        /** @var InstrumentRepositoryInterface $instrumentRepo */
        $instrumentRepo = app(InstrumentRepositoryInterface::class);
        $this->setItems($instrumentRepo->getElasticResourceBuilder()->get());
        return $this;
    }

    public function handle()
    {
        if (is_null($this->export)) {
            throw new Exception("Export property is required.");
        }

//        For local testing when no QUEUE is set up
//        if (App::environment('local')) {
//            ini_set('memory_limit', '2G');
//            set_time_limit(3000);
//        }

        $this->initExport();

        if (is_null($this->items)) {
            $this->setDefaultItems();
        }

        $jobs = [];
        foreach ($this->items as $instrument) {
            $jobs[] = new ProcessInstrumentJob($this->export, $instrument);
        }

        Bus::batch([
            $jobs,
            new WrapInstrumentsJob($this->export),
            new StoreInstrumentsExportJob($this->export)
        ])
            ->then(function (Batch $batch) {
                Log::info('Instrument export done');
            })
            ->catch(function (Batch $batch, Throwable $e) {
                Log::error('Instrument export failed');
            })
            ->name($this->export->getAttribute('mark'))
            ->dispatch();

        $this->finishExport();
    }

    private function initExport(): static
    {
        $this->export->fill([
            'type' => $this->type,
            'mark' => $this->getMark(),
            'status' => Export::STATUS_INITIATED
        ])->saveQuietly();
        return $this;
    }

    private function getMark()
    {
        $organisation = $this->export->organisation;
        $mark = "{$organisation->id}-{$this->type}-".date('dmyhis');
        $this->exportMark = $mark;
        return $mark;
    }

    private function failExport(): static
    {
        $this->export->fill([
            'status' => Export::STATUS_FAILED
        ])->saveQuietly();
        return $this;
    }

    private function finishExport(): static
    {
        $this->export->fill([
            'status' => Export::STATUS_DONE
        ])->saveQuietly();
        return $this;
    }
}
