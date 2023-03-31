<?php

namespace Vng\EvaCore\Services;

use Aws\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Util;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\Organisation;

class DownloadsService
{
    public static function getDownloadsDisk(): string
    {
        return config('filesystems.cloud', 's3');
    }

    public static function getDownloadsDirectory(Organisation $organisation = null): string
    {
        $downloadPath = config('filesystems.storage_paths.downloads');
        if (!is_null($organisation)) {
            $downloadPath .= '/' . $organisation->id .  '-' . $organisation->getSlugAttribute();
        }
        return Util::normalizePath($downloadPath);
    }

    public static function saveUploadedFile(UploadedFile $uploadedFile, Organisation $organisation, ?Download $download = null): Download
    {
        if (is_null($download)) {
            $download = new Download();
        }

        $originalFileName = $uploadedFile->getClientOriginalName();
        $filePath = $uploadedFile->store(
            static::getDownloadsDirectory($organisation),
            [
                'disk' => static::getDownloadsDisk(),
                'visibility' => 'private',
            ]
        );

        return $download->fill([
            'filename' => $originalFileName,
            'url' => $filePath
        ]);
    }

    public static function movePreUploadedFile(string $tempPath, Organisation $organisation, ?Download $download = null): Download
    {
        if (is_null($download)) {
            $download = new Download();
        }

        $filePath = str_replace('tmp/', static::getDownloadsDirectory($organisation) . '/', $tempPath);

        self::getStorage(static::getDownloadsDisk())->copy(
            $tempPath,
            $filePath
        );

        return $download->fill([
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
