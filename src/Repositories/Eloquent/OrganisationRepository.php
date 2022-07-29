<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\OrganisationCreateRequest;
use Vng\EvaCore\Http\Requests\OrganisationUpdateRequest;
use Vng\EvaCore\Interfaces\OrganisationEntityInterface;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;

class OrganisationRepository extends BaseRepository implements OrganisationRepositoryInterface
{
    public string $model = Organisation::class;

    public function new(): Organisation
    {
        return new $this->model();
    }

    public function create(OrganisationCreateRequest $request): Organisation
    {
        return $this->saveFromRequest($this->new(), $request);
    }

    public function update(Organisation $organisation, OrganisationUpdateRequest $request): Organisation
    {
        return $this->saveFromRequest($organisation, $request);
    }

    public function saveFromRequest(Organisation $organisation, FormRequest $request): Organisation
    {
        $organisation = $organisation->fill([]);
        $organisation->save();
        return $organisation;
    }

    /**
     * Associates the organisation entity to the organisation to create a double reference for easy lookup
     */
    public function associateOrganisationable(Model $organisationEntity): ?Organisation
    {
        if (!$organisationEntity instanceof OrganisationEntityInterface) {
            throw new \Exception('Model must implement OrganisationEntityInterface');
        }
        /** @var Organisation $organisation */
        $organisation = $organisationEntity->organisation;
        if (is_null($organisation)) {
            throw new \Exception('Model must belong to an organisation');
        }
        $organisation->organisationable()->associate($organisationEntity);
        return $organisation;
    }
}
