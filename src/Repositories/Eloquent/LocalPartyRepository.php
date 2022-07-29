<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\LocalPartyCreateRequest;
use Vng\EvaCore\Http\Requests\LocalPartyUpdateRequest;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Repositories\LocalPartyRepositoryInterface;

class LocalPartyRepository extends BaseRepository implements LocalPartyRepositoryInterface
{
    use SoftDeletableRepository;

    protected string $model = LocalParty::class;

    public function new(): LocalParty
    {
        return new $this->model;
    }

    public function create(LocalPartyCreateRequest $request): LocalParty
    {
        return $this->saveFromRequest($this->new(), $request);
    }

    public function update(LocalParty $localParty, LocalPartyUpdateRequest $request): LocalParty
    {
        return $this->saveFromRequest($localParty, $request);
    }

    public function saveFromRequest(LocalParty $localParty, FormRequest $request): LocalParty
    {
        $localParty = $localParty->fill([
            'name' => $request->input('name'),
        ]);
        $localParty->township()->associate($request->input('township_id'));
        $localParty->save();
        return $localParty;
    }
}
