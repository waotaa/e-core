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
        return $this->createFromRequest(new $this->model(), $request);
    }

    public function update(Download $download, DownloadUpdateRequest $request): Download
    {
        return $this->updateFromRequest($download, $request);
    }

    public function createFromRequest(Download $download, FormRequest $request): Download
    {
        $organisation = $this->getOrganisationFromRequest($request);
        $fileData = $this->storeFileFromRequest($organisation, $request);
        if (is_null($fileData)) {
            throw new \Exception('Invalid request. Missing file or key');
        }

        $download->fill($fileData);
        $download = $this->fillFromRequest($download, $request);
        $download->save();
        return $download;
    }

    public function updateFromRequest(Download $download, FormRequest $request): Download
    {
        $organisation = $this->getOrganisationFromRequest($request);
        $fileData = $this->storeFileFromRequest($organisation, $request);
        if (!is_null($fileData)) {
            $download->fill($fileData);
        }

        $download = $this->fillFromRequest($download, $request);
        $download->save();
        return $download;
    }

    private function getOrganisationFromRequest(FormRequest $request): Organisation
    {
        $organisationRepository = new OrganisationRepository();
        /** @var Organisation $organisation */
        $organisation = $organisationRepository->find($request->input('organisation_id'));
        if (is_null($organisation)) {
            throw new \Exception('invalid organisation provided');
        }
        return $organisation;
    }

    private function storeFileFromRequest(Organisation $organisation, FormRequest $request): ?array
    {
        if ($request->has('file')) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->file('file');
            $storedFile = DownloadStorageService::make()->setOrganisation($organisation)->storeUploadedFile($uploadedFile);
            return [
                'filename' => $storedFile->getFilename(),
                'url' => $storedFile->getPath()
            ];
        }

        if ($request->has('key')) {
            $filePath = DownloadStorageService::make()->setOrganisation($organisation)->movePreUploadedFile($request->input('key'));
            return [
                'filename' => $request->input('filename'),
                'url' => $filePath
            ];
        }

        return null;
    }

    private function fillFromRequest(Download $download, FormRequest $request): Download
    {
        $download->fill([
            'label' => $request->input('label'),
        ]);
        $organisation = $this->getOrganisationFromRequest($request);
        $download->organisation()->associate($organisation);
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
