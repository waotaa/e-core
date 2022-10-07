<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\EnvironmentCreateRequest;
use Vng\EvaCore\Http\Requests\EnvironmentUpdateRequest;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;

class EnvironmentRepository extends BaseRepository implements EnvironmentRepositoryInterface
{
    use SoftDeletableRepository;

    public string $model = Environment::class;

    public function create(EnvironmentCreateRequest $request): Environment
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Environment $environment, EnvironmentUpdateRequest $request): Environment
    {
        return $this->saveFromRequest($environment, $request);
    }

    public function saveFromRequest(Environment $environment, FormRequest $request): Environment
    {
        $environment->fill([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description_header' => $request->input('description_header'),
            'description' => $request->input('description'),
            'logo' => $request->input('logo'),
            'color_primary' => $request->input('color_primary'),
            'color_secondary' => $request->input('color_secondary'),
        ]);

        $environment->contact()->disassociate();
        if ($request->has('contact_id'))
        {
            $environment->contact()->associate($request->input('contact_id'));
        }

        $environment->save();
        return $environment;
    }

    public function attachFeaturedOrganisations(Environment $environment, string|array $organisationIds): Environment
    {
        $environment->featuredOrganisations()->syncWithoutDetaching((array) $organisationIds);
        return $environment;
    }

    public function detachFeaturedOrganisations(Environment $environment, string|array $organisationIds): Environment
    {
        $environment->featuredOrganisations()->detach((array) $organisationIds);
        return $environment;
    }
}
