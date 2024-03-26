<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Vng\EvaCore\Http\Requests\DownloadCreateRequest;
use Vng\EvaCore\Http\Requests\DownloadUpdateRequest;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Repositories\DownloadRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\Storage\DownloadStorageService;

class DownloadRepository extends BaseRepository implements DownloadRepositoryInterface
{
    use InstrumentOwnedEntityRepository;

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
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        /** @var Instrument $instrument */
        $instrument = $instrumentRepository->find($request->input('instrument_id'));
        if (is_null($instrument)) {
            throw new \Exception('invalid instrument provided');
        }
        $organisation = $instrument->organisation;
        if (is_null($organisation)) {
            throw new \Exception('instrument requires an organisation');
        }

        if ($request->has('file')) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->file('file');
            $storedFile = DownloadStorageService::make()->setOrganisation($organisation)->storeFile($uploadedFile);
            $download->fill([
                'filename' => $storedFile->getFilename(),
                'url' => $storedFile->getPath()
            ]);
        } elseif ($request->has('key')) {
            $filePath = DownloadStorageService::make()->setOrganisation($organisation)->movePreUploadedFile($request->input('key'));
            $download->fill([
                'filename' => $request->input('filename'),
                'url' => $filePath
            ]);
        } else {
            throw new \Exception('Invalid request. Missing file or key');
        }

        $download->fill([
            'label' => $request->input('label'),
        ]);
        $download->instrument()->associate($request->input('instrument_id'));
        $download->save();
        return $download;
    }
}
