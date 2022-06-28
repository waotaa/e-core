<?php

namespace Vng\EvaCore\Services\Instrument;

use Vng\EvaCore\ElasticResources\InstrumentResource;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Services\StorageService;
use Illuminate\Support\Collection;

class InstrumentExportService
{
    public static function exportAllInstruments($filenameAddition = null)
    {
        $instruments = Instrument::all();
        $instrumentsJson = static::createExportJson($instruments);
        $filePath = static::getFilePath($filenameAddition);
        StorageService::getStorage()->put($filePath, $instrumentsJson);
        return $filePath;
    }

    private static function createExportJson(Collection $instruments)
    {
        $instrumentResources = collect($instruments)->map(function(Instrument $instrument) {
            return InstrumentResource::make($instrument)->toArray();
        });
        return json_encode($instrumentResources, JSON_PRETTY_PRINT);
    }

    protected static function getFilePath($filenameAddition): string
    {
        $name_prefix = date('dmy');
        $filename = $name_prefix . '-instruments';
        if (!empty($filenameAddition)) {
            $filename = $name_prefix . '-' . $filenameAddition . '-instruments';
        }

        return static::getDirectory() . $filename.'.json';
    }

    protected static function getDirectory(): string
    {
        return 'exports/';
    }
}
