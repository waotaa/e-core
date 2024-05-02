<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Http\Requests\DownloadCreateRequest;
use Vng\EvaCore\Http\Requests\DownloadUpdateRequest;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\DownloadRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\Storage\DownloadStorageService;

class DownloadRepository extends BaseRepository implements DownloadRepositoryInterface
{
    use OwnedEntityRepository;

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
        $organisationRepository = new OrganisationRepository();
        /** @var Organisation $organisation */
        $organisation = $organisationRepository->find($request->input('organisation_id'));
        if (is_null($organisation)) {
            throw new \Exception('invalid organisation provided');
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
        $download->organisation()->associate($organisation);
        $download->save();
        return $download;
    }

    public function attachInstruments(Download $download, string|array $instrumentIds): Download
    {
        $instrumentIds = (array) $instrumentIds;
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instrumentRepository
            ->findMany($instrumentIds)
            ->each(
                function (Instrument $instrument) use ($download) {
                    Gate::authorize('attachInstrument', [$download, $instrument]);
                }
            );

        $download->instruments()->syncWithoutDetaching($instrumentIds);
        return $download;
    }

    public function detachInstruments(Download $download, string|array $instrumentIds): Download
    {
        $instrumentIds = (array) $instrumentIds;
        /** @var InstrumentRepositoryInterface $instrumentRepository */
        $instrumentRepository = app(InstrumentRepositoryInterface::class);
        $instrumentRepository
            ->findMany($instrumentIds)
            ->each(
                function (Instrument $instrument) use ($download) {
                    Gate::authorize('detachInstrument', [$download, $instrument]);
                }
            );

        $download->instruments()->detach($instrumentIds);
        return $download;
    }
}
