<?php

namespace Vng\EvaCore\Jobs\Export;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Vng\EvaCore\ElasticResources\InstrumentResource;
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
        protected string $mark,
        protected Instrument $instrument
    )
    {}

    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            Log::warning("Skipped processing instrument {$this->instrument->id} for export {$this->mark}. Batch cancelled");
            return;
        }

        Log::info("Processing instrument {$this->instrument->id} for export {$this->mark}");

        // Transform instrument to array
        $transformedItem = InstrumentResource::make($this->instrument)->toArray();
        $jsonItem = json_encode($transformedItem, JSON_PRETTY_PRINT);

        $result = $this->storeFile($jsonItem, $this->mark, "{$this->instrument->id}.json");

        Log::debug('done', [
            'success' => !is_null($result)
        ]);
    }
}
