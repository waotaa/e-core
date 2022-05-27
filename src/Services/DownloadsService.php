<?php

namespace Vng\EvaCore\Services;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Util;
use Vng\EvaCore\Models\Download;

class DownloadsService
{
    public static function getDownloadsDisk(): string
    {
        return App::environment('local')
            ? config('filesystems.default', 'local')
            : config('filesystems.cloud', 's3');
    }

    public static function getDownloadsDirectory(): string
    {
        $downloadPath = config('filesystems.directory_paths.downloads');
        return Util::normalizePath($downloadPath);
    }

    public static function saveUploadedFile(UploadedFile $uploadedFile): Download
    {
        $originalFileName = $uploadedFile->getClientOriginalName();
        $filePath = $uploadedFile->store(
            static::getDownloadsDirectory(),
            [
                'disk' => static::getDownloadsDisk(),
                'visibility' => 'private',
            ]
        );

        return new Download([
            'filename' => $originalFileName,
            'url' => $filePath
        ]);
    }

    public static function downloadDownloadFile(Download $download)
    {
        return Storage::disk(static::getDownloadsDisk())->download($download->url, $download->filename);
    }

    public static function deleteDownloadFile(Download $download)
    {
        Storage::disk(static::getDownloadsDisk())->delete($download->url);
    }

    public static function fileExists($path, $disk = null): bool
    {
        return self::getStorage($disk)->exists($path);
    }

    public static function getStorage($disk = null): Filesystem
    {
        if ($disk) {
            return Storage::disk($disk);
        }
        if (App::environment('local')) {
            return Storage::disk('local');
        }
        return Storage::disk('s3');
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
