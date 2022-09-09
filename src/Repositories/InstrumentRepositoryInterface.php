<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\InstrumentCreateRequest;
use Vng\EvaCore\Http\Requests\InstrumentUpdateRequest;
use Vng\EvaCore\Models\Instrument;

interface InstrumentRepositoryInterface extends OwnedEntityRepositoryInterface
{
    public function create(InstrumentCreateRequest $request): Instrument;
    public function update(Instrument $instrument, InstrumentUpdateRequest $request): Instrument;

    public function attachClientCharacteristics(Instrument $instrument, string|array $clientCharacteristicIds): Instrument;
    public function detachClientCharacteristics(Instrument $instrument, string|array $clientCharacteristicIds): Instrument;

    public function attachGroupForms(Instrument $instrument, string|array $groupFormIds): Instrument;
    public function detachGroupForms(Instrument $instrument, string|array $groupFormIds): Instrument;

    public function attachTargetGroups(Instrument $instrument, string|array $targetGroupIds): Instrument;
    public function detachTargetGroups(Instrument $instrument, string|array $targetGroupIds): Instrument;

    public function attachTiles(Instrument $instrument, string|array $tileIds): Instrument;
    public function detachTiles(Instrument $instrument, string|array $tileIds): Instrument;


    public function attachAvailableRegions(Instrument $instrument, string|array $regionIds): Instrument;
    public function detachAvailableRegions(Instrument $instrument, string|array $regionIds): Instrument;

    public function attachAvailableTownships(Instrument $instrument, string|array $townshipIds): Instrument;
    public function detachAvailableTownships(Instrument $instrument, string|array $townshipIds): Instrument;

    public function attachAvailableNeighbourhoods(Instrument $instrument, string|array $neighbourhoodIds): Instrument;
    public function detachAvailableNeighbourhoods(Instrument $instrument, string|array $neighbourhoodIds): Instrument;
}
