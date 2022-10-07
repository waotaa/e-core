<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\TileCreateRequest;
use Vng\EvaCore\Http\Requests\TileUpdateRequest;
use Vng\EvaCore\Models\Tile;

interface TileRepositoryInterface extends BaseRepositoryInterface
{
    public function create(TileCreateRequest $request): Tile;
    public function update(Tile $tile, TileUpdateRequest $request): Tile;

    public function attachInstruments(Tile $tile, string|array $instrumentIds): Tile;
    public function detachInstruments(Tile $tile, string|array $instrumentIds): Tile;
}
