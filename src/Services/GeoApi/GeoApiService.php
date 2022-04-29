<?php

namespace Vng\EvaCore\Services\GeoApi;

use Vng\EvaCore\Services\StorageService;

abstract class GeoApiService
{
    /**
     * Get data from the API
     * The only public method here. And the only way you should try to get data from the api
     */
    public static function getData($includeGeo = false, $allowFromCache = false): array
    {
        if ($allowFromCache) {
            return self::loadOrFetchAndCacheData($includeGeo);
        }

        return static::getDataFromApi($includeGeo);
    }

    protected static function loadOrFetchAndCacheData($includeGeo = false): array
    {
        $filePath = static::getFilePath($includeGeo);
        // Check if existing data is older than 24 hours
        static::checkCacheFile($filePath);

        if (!StorageService::fileExists($filePath, static::getDisk())) {
            return static::getDataFromApi($includeGeo);
        }
        return static::getDataFromCache($includeGeo);
    }

    // Fetch the api contents and turn it into usable data
    protected static function getDataFromApi($includeGeo = false): array
    {
        $contents = static::fetchAndCacheContents($includeGeo);
        return static::transformResponseToArray($contents);
    }

    private static function transformResponseToArray(string $responseContents): array
    {
        return json_decode($responseContents, true);
    }

    protected static function getDataFromCache($includeGeo = false)
    {
        return StorageService::loadJson(self::getFilePath($includeGeo), static::getDisk());
    }

    protected static function checkCacheFile($filePath)
    {
        if (StorageService::fileExists($filePath, static::getDisk())) {
            $lastModified = StorageService::getStorage(static::getDisk())->lastModified($filePath);
            if ($lastModified < strtotime('-24 hours')) {
                static::clearCacheFile($filePath);
            }
        }
    }

    public static function clearCache($includeGeo = false)
    {
        $filePath = static::getFilePath($includeGeo);
        static::clearCacheFile($filePath);
    }

    protected static function clearCacheFile($filePath)
    {
        if (StorageService::fileExists($filePath, static::getDisk())) {
            StorageService::getStorage(static::getDisk())->delete($filePath);
        }
    }

    // Fetch the api data
    private static function fetchAndCacheContents($includeGeo = false): string
    {
        $contents = static::fetchApiContents($includeGeo);
        // Cache it
        static::cacheData(self::getFilePath($includeGeo), $contents);
        return $contents;
    }

    // The method used to fetch the data from the api
    abstract protected static function fetchApiContents($includeGeo = false): string;

    private static function cacheData($filename, string $contents): bool
    {
        return StorageService::getStorage(static::getDisk())
            ->put($filename, $contents);
    }

    // FILE LOCATION

    // The file name where we cache the api result
    abstract protected static function getFileName();

    public static function getFilePath($includeGeo = false): string
    {
        $fileName = static::getFileName();
        if ($includeGeo) {
            $fileName .= '-geo';
        }
        return static::getFilePathByName($fileName);
    }

    protected static function getFilePathByName($name): string
    {
        return static::getDirectory() . $name.'.json';
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
