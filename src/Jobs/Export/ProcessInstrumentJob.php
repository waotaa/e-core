<?php

namespace Vng\EvaCore\Jobs\Export;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\ElasticResources\InstrumentWerknemersdienstverleningResource;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Instrument;

class ProcessInstrumentJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        Batchable,
        SerializesModels,
        useTempLocalStorageTrait;

    public function __construct(
        protected Export $export,
        protected Instrument $instrument
    )
    {}

    public function handle(): void
    {
        $mark = $this->export->getAttribute('mark');

        if ($this->batch()->cancelled()) {
            Log::warning("Skipped processing instrument {$this->instrument->id} for export {$mark}. Batch cancelled");
            return;
        }

        Log::info("Processing instrument {$this->instrument->id} for export {$mark}");

        // Transform instrument to array
        $transformedItem = InstrumentWerknemersdienstverleningResource::make($this->instrument)->toArray();
        $jsonItem = json_encode($transformedItem, JSON_PRETTY_PRINT);

        $result = $this->storeFile($jsonItem, $mark, "{$this->instrument->id}.json");

        Log::debug('done', [
            'success' => !is_null($result)
        ]);

        $this->export->fill([
            'progress' => $this->batch()->progress()
        ])->saveQuietly();
    }
}
