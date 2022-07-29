<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\NationalPartyCreateRequest;
use Vng\EvaCore\Http\Requests\NationalPartyUpdateRequest;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Repositories\NationalPartyRepositoryInterface;

class NationalPartyRepository extends BaseRepository implements NationalPartyRepositoryInterface
{
    use SoftDeletableRepository;

    protected string $model = NationalParty::class;

    public function new(): NationalParty
    {
        return new $this->model;
    }

    public function create(NationalPartyCreateRequest $request): NationalParty
    {
        return $this->saveFormRequest($this->new(), $request);
    }

    public function update(NationalParty $nationalParty, NationalPartyUpdateRequest $request): NationalParty
    {
        return $this->saveFormRequest($nationalParty, $request);
    }

    public function saveFormRequest(NationalParty $nationalParty, FormRequest $request): NationalParty
    {
        $nationalParty = $nationalParty->fill([
            'name' => $request->input('name'),
        ]);
        $nationalParty->save();
        return $nationalParty;
    }
}
