<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ExportCreateRequest;
use Vng\EvaCore\Models\Export;

interface ExportRepositoryInterface extends OwnedEntityRepositoryInterface
{
    public function create(ExportCreateRequest $request): Export;
}
