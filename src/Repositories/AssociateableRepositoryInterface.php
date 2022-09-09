<?php

namespace Vng\EvaCore\Repositories;

use Vng\EvaCore\Http\Requests\AssociateableCreateRequest;
use Vng\EvaCore\Http\Requests\AssociateableUpdateRequest;
use Vng\EvaCore\Models\Associateable;

/**
 * @deprecated
 */
interface AssociateableRepositoryInterface extends BaseRepositoryInterface
{
    public function create(AssociateableCreateRequest $request): Associateable;
    public function update(Associateable $associateable, AssociateableUpdateRequest $request): Associateable;
}
