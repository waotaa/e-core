<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\ProfessionalCreateRequest;
use Vng\EvaCore\Http\Requests\ProfessionalUpdateRequest;
use Vng\EvaCore\Interfaces\IsManagerInterface;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Repositories\EnvironmentRepositoryInterface;
use Vng\EvaCore\Repositories\ProfessionalRepositoryInterface;

class ProfessionalRepository extends BaseRepository implements ProfessionalRepositoryInterface
{
    public string $model = Professional::class;

    public function create(ProfessionalCreateRequest $request): Professional
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Professional $professional, ProfessionalUpdateRequest $request): Professional
    {
        return $this->saveFromRequest($professional, $request);
    }

    public function saveFromRequest(Professional $professional, FormRequest $request): Professional
    {
        $professional->fill([
//            'username' => $request->input('username'),
            'email' => $request->input('email'),
//            'email_verified' => $request->input('email_verified'),
//            'last_seen_at' => $request->input('last_seen_at'),
//            'enabled' => $request->input('enabled'),
//            'user_status' => $request->input('user_status'),
        ]);
        $professional->environment()->associate($request->input('environment_id'));
        $professional->save();
        return $professional;
    }

    public function addForUserConditions(Builder $query, IsManagerInterface $user)
    {
        /** @var EnvironmentRepositoryInterface $environmentRepository */
        $environmentRepository = app(EnvironmentRepositoryInterface::class);

        // return professionals with an environment...
        return $query->whereHas('environment', function (Builder $query) use ($environmentRepository, $user) {
            // ...managed by the user
            $environmentRepository->addForUserConditions($query, $user);
        });
    }

    public function getQueryItemsManagedByUser(IsManagerInterface $user): Builder
    {
        return $this->addForUserConditions($this->builder(), $user);
    }
}
