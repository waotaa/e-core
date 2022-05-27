<?php

namespace Vng\EvaCore\Services;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class StorageService
{
    public static function fileExists($path, $disk = null): bool
    {
        return self::getStorage($disk)->exists($path);
    }

    public static function getDisk(): string
    {
        return App::environment('local') ? 'local' : 's3';
    }

    public static function getStorage($disk = null): Filesystem
    {
        if ($disk) {
            return Storage::disk($disk);
        }
        return Storage::disk(static::getDisk());
    }

    /**
     * @param $path
     * @param null $disk
     * @return array
     * @throws FileNotFoundException
     */
    public static function loadJson($path, $disk = null): array
    {
        if (!static::fileExists($path, $disk)) {
            throw new Exception('file does not exists');
        }
        $encodedJson = static::getStorage($disk)->get($path);
        $decodedJson = json_decode($encodedJson, true);
        if (is_null($decodedJson)) {
            throw new Exception('unable to json decode file');
        }
        return $decodedJson;
    }
}
