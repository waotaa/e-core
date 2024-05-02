<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\DownloadCreateRequest;
use Vng\EvaCore\Http\Requests\DownloadUpdateRequest;
use Vng\EvaCore\Models\Download;

interface DownloadRepositoryInterface extends OwnedEntityRepositoryInterface
{
    public function create(DownloadCreateRequest $request): Download;
    public function update(Download $download, DownloadUpdateRequest $request): Download;

    public function attachInstruments(Download $download, string|array $instrumentIds): Download;
    public function detachInstruments(Download $download, string|array $instrumentIds): Download;
}
