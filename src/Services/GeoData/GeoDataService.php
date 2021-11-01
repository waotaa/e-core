<?php

namespace Vng\EvaCore\Services\GeoData;

use Vng\EvaCore\Services\StorageService;
use Exception;
use Illuminate\Support\Collection;

abstract class GeoDataService
{
    public static function createDataSnapshot()
    {
        $newData = self::assembleData();
        static::storeData(static::getTimestampedFileName(), $newData);
    }

    public static function createSourceData()
    {
        $newData = self::assembleData();
        static::storeData(static::getFileName(), $newData);
    }

    private static function assembleData()
    {
        $baseData = static::fetchBaseData();
        // Add data from other sources?
        return $baseData;
    }

    abstract public static function fetchBaseData(): Collection;

    public static function createBasicGeoCollectionFromData(array $data): Collection
    {
        return collect($data)->map(function ($entry) {
            return static::createBasicGeoModelFromDataEntry($entry);
        });
    }

    abstract public static function createBasicGeoModelFromDataEntry(array $entry): BasicGeoModel;

    /**
     * @return array
     * @throws Exception
     */
    public static function loadData(): array
    {
        if(!StorageService::fileExists(static::getFilePath(static::getFileName()), static::getDisk())) {
            throw new Exception('No data found');
        }
        return StorageService::loadJson(static::getFilePath(static::getFileName()), static::getDisk());
    }

    public static function loadOrCreateData(): array
    {
        if(!StorageService::fileExists(static::getFilePath(static::getFileName()), static::getDisk())) {
            static::createSourceData();
        }
        return static::loadData();
    }

    protected static function storeData(string $filename, string $data)
    {
        StorageService::getStorage(static::getDisk())->put(static::getFilePath($filename), $data);
    }

    protected static function getFilePath($fileName): string
    {
        return static::getDirectory() . $fileName . '.json';
    }

    protected static function getTimestampedFileName(): string
    {
        return static::getFileName() . '-' . date('dmy');
    }

    abstract protected static function getFileName(): string;

    protected static function getDirectory(): string
    {
        return 'source-data/';
    }

    private static function getDisk(): string
    {
        return config('filesystems.geo');
    }
}
