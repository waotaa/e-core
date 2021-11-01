<?php

namespace Vng\EvaCore\Services\GeoApi;

use Vng\EvaCore\Services\StorageService;

abstract class GeoApiService
{
    abstract protected static function getFileName();

    abstract public static function fetchApiContents($includeGeo = false): string;

    public static function fetchApiData($includeGeo = false): array
    {
        $contents = static::fetchApiContents($includeGeo);
        return static::transformResponseToArray($contents);
    }

    public static function transformResponseToArray(string $responseContents): array
    {
        return json_decode($responseContents, true);
    }

    public static function getData($includeGeo = false): array
    {
        return self::loadOrFetchAndStoreData($includeGeo);
    }

    public static function loadOrFetchAndStoreData($includeGeo = false): array
    {
        if (!StorageService::fileExists(self::getFilePath($includeGeo), static::getDisk())) {
            static::fetchAndStoreData($includeGeo);
        }
        return StorageService::loadJson(self::getFilePath($includeGeo), static::getDisk());
    }

    public static function fetchAndStoreData($includeGeo = false): string
    {
        $contents = static::fetchApiContents($includeGeo);
        StorageService::getStorage(static::getDisk())->put(self::getFilePath($includeGeo), $contents);
        return $contents;
    }

    public static function getFilePath($includeGeo = false): string
    {
        $fileName = static::getFileName();
        if ($includeGeo) {
            $fileName .= '-geo';
        }
        return self::getFilePathByName($fileName);
    }

    protected static function getFilePathByName($name): string
    {
        return self::getDirectory() . $name.'.json';
    }

    protected static function getDirectory(): string
    {
        return 'api-results/';
    }

    private static function getDisk(): string
    {
        return config('filesystems.geo');
    }
}
