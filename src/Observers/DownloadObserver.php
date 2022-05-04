<?php

namespace Vng\EvaCore\Observers;

use Vng\EvaCore\Events\ElasticRelatedResourceChanged;
use Vng\EvaCore\Events\InstrumentSaved;
use Vng\EvaCore\Models\Download;

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
        if (!is_null($download->instrument)) {
            ElasticRelatedResourceChanged::dispatch($download->instrument, $download);
            InstrumentSaved::dispatch($download->instrument);
        }
    }
}
