<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Vng\EvaCore\Http\Requests\EnvironmentCreateRequest;
use Vng\EvaCore\Http\Requests\EnvironmentDetailsUpdateRequest;
use Vng\EvaCore\Http\Requests\EnvironmentUpdateRequest;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Services\Storage\LogoStorageService;

class EnvironmentRepository extends BaseRepository implements EnvironmentRepositoryInterface
{
    use OwnedEntityRepository, SoftDeletableRepository;

    public string $model = Environment::class;

    public function create(EnvironmentCreateRequest $request): Environment
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Environment $environment, EnvironmentUpdateRequest $request): Environment
    {
        return $this->saveFromRequest($environment, $request);
    }

    public function updateDetails(Environment $environment, EnvironmentDetailsUpdateRequest $request): Environment
    {
        return $this->saveFromRequest($environment, $request);
    }

    public function saveFromRequest(Environment $environment, FormRequest $request): Environment
    {
        $organisationRepository = new OrganisationRepository();

        if ($request->has('organisation_id')) {
            $organisation = $organisationRepository->find($request->input('organisation_id'));
            if (is_null($organisation)) {
                throw new \Exception('invalid organisation provided');
            }
            $environment->organisation()->associate($organisation);
        }

        $logo = $request->input('logo');
        if ($request->hasFile('logo')) {
            /** @var UploadedFile $uploadedFile */
            $logo = $request->file('logo');
            // Check if the file actually exists on the server
            if ($logo->isValid()) {
                $storedFile = LogoStorageService::make()
                    ->setOrganisation($environment->organisation)
                    ->storeFile($logo);
                $logo = $storedFile->getPath();
                $environment->fill([
                    'logo' => $logo
                ]);
            }
        } elseif (!is_null($logo)) {
            $environment->fill([
                'logo' => $logo
            ]);
        }

        $environment->fill([
            // optional in edit requests
            'name' => $request->has('name') ? $request->input('name') : $environment->name,
            'slug' => $request->has('slug') ? $request->input('slug') : $environment->slug,
            'url' => $request->has('url') ? $request->input('url') : $environment->url,

            'description_header' => $request->input('description_header'),
            'description' => $request->input('description'),
            'color_primary' => $request->input('color_primary'),
            'color_secondary' => $request->input('color_secondary'),
        ]);

        $environment->contact()->disassociate();
        if ($request->has('contact_id')) {
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
