<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Vng\EvaCore\Http\Requests\OrganisationCreateRequest;
use Vng\EvaCore\Http\Requests\OrganisationUpdateRequest;
use Vng\EvaCore\Interfaces\OrganisationEntityInterface;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Repositories\ManagerRepositoryInterface;
use Vng\EvaCore\Repositories\OrganisationRepositoryInterface;

class OrganisationRepository extends BaseRepository implements OrganisationRepositoryInterface
{
    use SoftDeletableRepository;

    public string $model = Organisation::class;

    public function findBySlug(string $slug)
    {
        return $this->addSlugCondition($this->builder(), $slug)->get();
    }

    public function addSlugCondition(Builder $query, $slug): Builder
    {
        return $query->where(function (Builder $query) use ($slug) {
            $query->whereHas('localParty', function (Builder $query) use ($slug) {
                $query->where('slug', $slug);
            })->orWhereHas('regionalParty', function (Builder $query) use ($slug) {
                $query->where('slug', $slug);
            })->orWhereHas('nationalParty', function (Builder $query) use ($slug) {
                $query->where('slug', $slug);
            })->orWhereHas('partnership', function (Builder $query) use ($slug) {
                $query->where('slug', $slug);
            });
        });
    }

    public function addManagerIsMemberCondition(Builder $query, Manager $manager): Builder
    {
        return $query->whereHas('managers', function (Builder $query) use ($manager) {
            $query->where('managers.id', $manager->id);
        });
    }

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

    public function attachManagers(Organisation $organisation, string|array $managerIds): Organisation
    {
        $managerIds = (array) $managerIds;
        /** @var ManagerRepositoryInterface $managerRepo */
        $managerRepo = app(ManagerRepositoryInterface::class);
        $managers = $managerRepo->builder()->whereIn('id', $managerIds)->get();
        $managers->each(fn (Manager $manager) => Gate::authorize('attachManager', [$organisation, $manager]));

        $organisation->managers()->syncWithoutDetaching($managerIds);
        return $organisation;
    }

    public function detachManagers(Organisation $organisation, string|array $managerIds): Organisation
    {
        $managerIds = (array) $managerIds;
        /** @var ManagerRepositoryInterface $managerRepo */
        $managerRepo = app(ManagerRepositoryInterface::class);
        $managers = $managerRepo->builder()->whereIn('id', $managerIds)->get();
        $managers->each(fn (Manager $manager) => Gate::authorize('detachManager', [$organisation, $manager]));

        $organisation->managers()->detach($managerIds);
        return $organisation;
    }

    public function attachFeaturingEnvironments(Organisation $organisation, string|array $environmentIds): Organisation
    {
        $organisation->featuringEnvironments()->syncWithoutDetaching((array) $environmentIds);
        return $organisation;
    }

    public function detachFeaturingEnvironments(Organisation $organisation, string|array $environmentIds): Organisation
    {
        $organisation->featuringEnvironments()->detach((array) $environmentIds);
        return $organisation;
    }

    public function attachContacts(Organisation $organisation, string|array $contactIds): Organisation
    {
        $organisation->contacts()->syncWithoutDetaching((array) $contactIds);
        return $organisation;
    }

    public function detachContacts(Organisation $organisation, string|array $contactIds): Organisation
    {
        $organisation->contacts()->detach((array) $contactIds);
        return $organisation;
    }
}
