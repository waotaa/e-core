<?php

namespace Vng\EvaCore\Services\Storage;

class InternalStorageService extends AbstractStorageService
{
    public function getBasePath(): string
    {
        return 'internal';
    }
}