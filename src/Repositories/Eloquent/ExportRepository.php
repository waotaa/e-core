<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Enums\ExportStatusEnum;
use Vng\EvaCore\Http\Requests\ExportCreateRequest;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\ExportRepositoryInterface;

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
            'type' => $request->input('type'),
            'status' => ExportStatusEnum::created()
        ]);
        $export->organisation()->associate($organisation);
        $export->save();
        return $export;
    }
    
    public function new(): Model
    {
        $export = parent::new();
        $export->fill([
            'status' => ExportStatusEnum::created()
        ]);
        return $export;
    }

    public function newForOrganisation(Organisation $organisation): Export
    {
        /** @var Export $export */
        $export = $this->new();
        $export->organisation()->associate($organisation);
        return $export;
    }
}
