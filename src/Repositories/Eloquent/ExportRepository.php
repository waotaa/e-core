<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Enums\ExportStatusEnum;
use Vng\EvaCore\Http\Requests\DownloadCreateRequest;
use Vng\EvaCore\Http\Requests\DownloadUpdateRequest;
use Vng\EvaCore\Http\Requests\ExportCreateRequest;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\DownloadRepositoryInterface;
use Vng\EvaCore\Repositories\ExportRepositoryInterface;
use Vng\EvaCore\Repositories\InstrumentRepositoryInterface;
use Vng\EvaCore\Services\Storage\DownloadStorageService;

class ExportRepository extends BaseRepository implements ExportRepositoryInterface
{
    use OwnedEntityRepository;

    public string $model = Export::class;

    public function create(ExportCreateRequest $request): Export
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function saveFromRequest(Export $export, FormRequest $request): Export
    {
        $organisationRepository = new OrganisationRepository();
        /** @var Organisation $organisation */
        $organisation = $organisationRepository->find($request->input('organisation_id'));
        if (is_null($organisation)) {
            throw new \Exception('invalid organisation provided');
        }

        $export->fill([
            'label' => $request->input('label'),
            'status' => ExportStatusEnum::created()
        ]);
        $export->organisation()->associate($organisation);
        $export->save();
        return $export;
    }
}
