<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\LinkCreateRequest;
use Vng\EvaCore\Http\Requests\LinkUpdateRequest;
use Vng\EvaCore\Models\Link;

interface LinkRepositoryInterface extends BaseRepositoryInterface
{
    public function create(LinkCreateRequest $request): Link;
    public function update(Link $download, LinkUpdateRequest $request): Link;
}
