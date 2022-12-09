<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\DownloadCreateRequest;
use Vng\EvaCore\Http\Requests\DownloadUpdateRequest;
use Vng\EvaCore\Models\Download;

interface DownloadRepositoryInterface extends InstrumentOwnedEntityRepositoryInterface
{
    public function create(DownloadCreateRequest $request): Download;
    public function update(Download $download, DownloadUpdateRequest $request): Download;
}
