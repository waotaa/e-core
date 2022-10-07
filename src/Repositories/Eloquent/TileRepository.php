<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\TileCreateRequest;
use Vng\EvaCore\Http\Requests\TileUpdateRequest;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Repositories\TileRepositoryInterface;

class TileRepository extends BaseRepository implements TileRepositoryInterface
{
    public string $model = Tile::class;

    public function create(TileCreateRequest $request): Tile
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Tile $tile, TileUpdateRequest $request): Tile
    {
        return $this->saveFromRequest($tile, $request);
    }

    public function saveFromRequest(Tile $tile, FormRequest $request): Tile
    {
        $tile->fill([
            'name' => $request->input('name'),
            'sub_title' => $request->input('sub_title'),
            'excerpt' => $request->input('excerpt'),
            'description' => $request->input('description'),
            'list' => $request->input('list'),
            'crisis_description' => $request->input('crisis_description'),
            'crisis_services' => $request->input('crisis_services'),
            'key' => $request->input('key'),
            'position' => $request->input('position'),
        ]);
        $tile->save();
        return $tile;
    }

    public function attachInstruments(Tile $tile, string|array $instrumentIds): Tile
    {
        $tile->instruments()->syncWithoutDetaching((array) $instrumentIds);
        return $tile;
    }

    public function detachInstruments(Tile $tile, string|array $instrumentIds): Tile
    {
        $tile->instruments()->detach((array) $instrumentIds);
        return $tile;
    }
}
