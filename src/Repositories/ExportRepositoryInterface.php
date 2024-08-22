<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\ExportCreateRequest;
use Vng\EvaCore\Models\Export;
use Vng\EvaCore\Models\Organisation;

interface ExportRepositoryInterface extends OwnedEntityRepositoryInterface
{
    public function create(ExportCreateRequest $request): Export;

    public function newForOrganisation(Organisation $organisation): Export;
}
