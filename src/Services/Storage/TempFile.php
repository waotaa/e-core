<?php

namespace Vng\EvaCore\Services\Storage;

use Illuminate\Support\Facades\Log;

class TempFile
{
    protected string $tempFilePath;
    protected $fileHandle;

    public function __construct(
        protected string $filename
    )
    {
        $extension = pathinfo($this->filename, PATHINFO_EXTENSION);
        $this->tempFilePath = tempnam(sys_get_temp_dir(), 'tmp') . '.' . $extension;
        Log::info("TempFile created at path: {$this->tempFilePath}");
        $this->open();
    }

    public static function make(string $filename): self
    {
        return new self($filename);
    }

    public function getPath(): string
    {
        return realpath($this->tempFilePath);
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function write($data)
    {
        $this->open();
        fwrite($this->fileHandle, $data);
//        Log::info("Written data to TempFile at path: {$this->tempFilePath}");
        return $this;
    }

    public function open()
    {
        if (!$this->fileHandle) {
            $this->fileHandle = fopen($this->tempFilePath, 'w');
            Log::info("Opened TempFile at path: {$this->tempFilePath}");
        }
        return $this;
    }

    public function close()
    {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
            Log::info("Closed TempFile at path: {$this->tempFilePath}");
        }
        return $this;
    }

    public function __destruct()
    {
        $this->close();
        if (file_exists($this->tempFilePath)) {
            unlink($this->tempFilePath);
            Log::info("Deleted TempFile at path: {$this->tempFilePath}");
        }
    }
}