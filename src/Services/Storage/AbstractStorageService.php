<?php

namespace Vng\EvaCore\Services\Storage;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Util;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vng\EvaCore\Models\Organisation;

abstract class AbstractStorageService
{
    protected $visibility = 'private';

    public static function make(): static
    {
        return new static();
    }

    abstract protected function getBasePath(): string;

    public function getStorageDiskName(): string
    {
        return config('filesystems.cloud', 's3');
    }

    public function getStorageDisk($disk = null): Filesystem
    {
        if ($disk) {
            return Storage::disk($disk);
        }
        return Storage::disk($this->getStorageDiskName());
    }

    protected function getStorageDirectory(): string
    {
        $basePath = $this->getBasePath();
        $basePath = Str::finish($basePath, '/');
        return Util::normalizePath($basePath);
    }

    public function storeFile(UploadedFile $uploadedFile): StoredFile
    {
        $originalFileName = $uploadedFile->getClientOriginalName();
        $storagePath = $this->getStorageDirectory();

        $filePath = $this->getStorageDisk()
            ->put($storagePath, $uploadedFile, $this->visibility);

        return new StoredFile($originalFileName, $filePath);
    }

    public function movePreUploadedFile(string $tempPath): string
    {
        $filePath = str_replace('tmp/', $this->getStorageDirectory() . '/', $tempPath);

        $this->getStorageDisk()->copy(
            $tempPath,
            $filePath
        );

        return $filePath;
    }

    public function getFileUrl($filePath): string
    {
        return $this->getStorageDisk()->url($filePath);
    }

    public function downloadFile($filePath, $fileName): StreamedResponse
    {
        return $this->getStorageDisk()->download($filePath, $fileName);
    }

    public function fileExists($path, $disk = null): bool
    {
        return $this->getStorageDisk($disk)->exists($path);
    }

    /**
     * @param $path
     * @param null $disk
     * @return array
     * @throws FileNotFoundException
     */
    public function loadJson($path, $disk = null): array
    {
        if (!static::fileExists($path, $disk)) {
            throw new Exception('file does not exists');
        }
        $encodedJson = $this->getStorageDisk($disk)->get($path);
        $decodedJson = json_decode($encodedJson, true);
        if (is_null($decodedJson)) {
            throw new Exception('unable to json decode file');
        }
        return $decodedJson;
    }

    public function deleteFile($filePath)
    {
        $this->getStorageDisk()->delete($filePath);
    }
}