<?php

namespace Vng\EvaCore\Services\Storage;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\Util;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function getStorageDirectory(): string
    {
        $basePath = $this->getBasePath();
        return Util::normalizePath($basePath);
    }

    public function storeUploadedFile(UploadedFile $uploadedFile): StoredFile
    {
        $originalFileName = $uploadedFile->getClientOriginalName();
        // The storage directory here does not have a name.
        $filePath = $this->storeFile($uploadedFile);

        return new StoredFile($originalFileName, $filePath);
    }

    public function storeFile($file, $filename = null): ?string
    {
        $storageDir = $this->getStorageDirectory();

        $filePath = Str::finish($storageDir, '/');
        if (!is_null($filename)) {
            $filePath .= $filename;
        }
        $succeeded = $this->getStorageDisk()
            ->put($filePath, $file, $this->visibility);
        return $succeeded ? $filePath : null;
    }

    public function createStream(string $filename): FileStream
    {
        $filePath = $this->getStorageDirectory() . '/' . $filename;
        $streamHandle = fopen('php://temp', 'w+');
        $this->getStorageDisk()->writeStream($filePath, $streamHandle);
        return new FileStream($streamHandle);
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

    public function downloadFile($filePath, $fileName = null): StreamedResponse
    {
        return $this->getStorageDisk()->download($filePath, $fileName);
    }

    public function fileExists($path, $disk = null): bool
    {
        return $this->getStorageDisk($disk)->exists($path);
    }

    public function files($directory, $disk = null): array
    {
        $storageDir = $this->getStorageDirectory();
        $filePath = Str::finish($storageDir, '/') . $directory;
        Log::info("Getting filenames for directory {$filePath}");

        if (!$this->fileExists($filePath)) {
            Log::error("Directory does not exist: {$filePath}");
        }

        $files = $this->getStorageDisk($disk)->files($filePath);
        Log::info(count($files) . " found");
        return $files;
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