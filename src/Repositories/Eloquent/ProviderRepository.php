<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\ProviderCreateRequest;
use Vng\EvaCore\Http\Requests\ProviderUpdateRequest;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Repositories\ProviderRepositoryInterface;

class ProviderRepository extends BaseRepository implements ProviderRepositoryInterface
{
    use OwnedEntityRepository, SoftDeletableRepository;

    public string $model = Provider::class;

    public function create(ProviderCreateRequest $request): Provider
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Provider $provider, ProviderUpdateRequest $request): Provider
    {
        return $this->saveFromRequest($provider, $request);
    }

    private function saveFromRequest(Provider $provider, FormRequest $request)
    {
        $organisationRepository = new OrganisationRepository();
        $organisation = $organisationRepository->find($request->input('organisation_id'));
        if (is_null($organisation)) {
            throw new \Exception('invalid organisation provided');
        }

        $provider = $provider->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
        ]);
//        $provider->owner()->associate($organisation->association);

        $provider->organisation()->associate($organisation);

        if ($request->has('address_id')) {
            $provider->address()->associate($request->input('address_id'));
        }

        $provider->save();

        return $provider;
    }
}
