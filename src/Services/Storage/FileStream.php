<?php

namespace Vng\EvaCore\Services\Storage;

class FileStream
{
    protected $streamHandle;

    public function __construct($streamHandle)
    {
        $this->streamHandle = $streamHandle;
    }

    public function write($data)
    {
        fwrite($this->streamHandle, $data);
    }

    public function close()
    {
        if ($this->streamHandle) {
            fclose($this->streamHandle);
            $this->streamHandle = null;
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}