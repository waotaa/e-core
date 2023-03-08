<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\GroupFormCreateRequest;
use Vng\EvaCore\Http\Requests\GroupFormUpdateRequest;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Models\Instrument;

interface GroupFormRepositoryInterface extends BaseRepositoryInterface
{
    public function create(GroupFormCreateRequest $request): GroupForm;
    public function update(GroupForm $groupForm, GroupFormUpdateRequest $request): GroupForm;

    public function attachInstruments(GroupForm $groupForm, string|array $instrumentIds): GroupForm;
    public function detachInstruments(GroupForm $groupForm, string|array $instrumentIds): GroupForm;
}
