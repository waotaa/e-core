<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\Instrument;

class DownloadObserver
{
    public function created(Download $download): void
    {
        $this->syncConnectedElasticResources($download);
    }

    public function updated(Download $download): void
    {
        $this->syncConnectedElasticResources($download);
    }

    public function deleted(Download $download): void
    {
        $this->syncConnectedElasticResources($download);
    }

    public function restored(Download $download): void
    {
        $this->syncConnectedElasticResources($download);
    }

    private function syncConnectedElasticResources(Download $download): void
    {
        $download->instruments->each(
            function(Instrument $instrument) use ($download) {
                ElasticRelatedResourceChanged::dispatch($instrument, $download);
                InstrumentSaved::dispatch($instrument);
            }
        );
    }
}
