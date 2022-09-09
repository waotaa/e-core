<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Vng\EvaCore\Http\Requests\DownloadCreateRequest;
use Vng\EvaCore\Http\Requests\DownloadUpdateRequest;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Repositories\DownloadRepositoryInterface;
use Vng\EvaCore\Services\DownloadsService;

class DownloadRepository extends BaseRepository implements DownloadRepositoryInterface
{
    public string $model = Download::class;

    public function create(DownloadCreateRequest $request): Download
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Download $download, DownloadUpdateRequest $request): Download
    {
        return $this->saveFromRequest($download, $request);
    }

    public function saveFromRequest(Download $download, FormRequest $request): Download
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->input('file');
        $download = DownloadsService::saveUploadedFile($uploadedFile, $download);
        $download->fill([
            'label' => $request->input('label'),
        ]);
        $download->instrument()->associate($request->input('instrument'));
        $download->save();
        return $download;
    }
}
