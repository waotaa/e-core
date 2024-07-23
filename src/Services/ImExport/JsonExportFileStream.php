<?php

namespace Vng\EvaCore\Services\ImExport;

use Vng\EvaCore\Services\Storage\FileStream;

class JsonExportFileStream
{
    protected bool $first = true;

    public function __construct(protected FileStream $fileStream)
    {
        $this->fileStream->write('[');
    }

    public function addItem($item)
    {
        if (!$this->first) {
            $this->fileStream->write(',');
        }
        $this->fileStream->write(json_encode($item));
        $this->first = false;
    }

    public function close()
    {
        if ($this->fileStream) {
            $this->fileStream->write(']');
            $this->fileStream->close();
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}