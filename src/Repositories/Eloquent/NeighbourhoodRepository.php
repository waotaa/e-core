<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\NeighbourhoodCreateRequest;
use Vng\EvaCore\Http\Requests\NeighbourhoodUpdateRequest;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Repositories\NeighbourhoodRepositoryInterface;

class NeighbourhoodRepository extends BaseRepository implements NeighbourhoodRepositoryInterface
{
    public string $model = Neighbourhood::class;

    public function create(NeighbourhoodCreateRequest $request): Neighbourhood
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Neighbourhood $neighbourhood, NeighbourhoodUpdateRequest $request): Neighbourhood
    {
        return $this->saveFromRequest($neighbourhood, $request);
    }

    public function saveFromRequest(Neighbourhood $neighbourhood, FormRequest $request): Neighbourhood
    {
        $neighbourhood->fill([
            'name' => $request->input('name'),
        ]);
        $neighbourhood->save();
        return $neighbourhood;
    }
}
