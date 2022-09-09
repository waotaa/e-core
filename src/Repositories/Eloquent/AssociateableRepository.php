<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\AssociateableCreateRequest;
use Vng\EvaCore\Http\Requests\AssociateableUpdateRequest;
use Vng\EvaCore\Models\Associateable;
use Vng\EvaCore\Repositories\AssociateableRepositoryInterface;

/**
 * @deprecated
 */
class AssociateableRepository extends BaseRepository implements AssociateableRepositoryInterface
{
    protected string $model = Associateable::class;

    public function create(AssociateableCreateRequest $request): Associateable
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Associateable $associateable, AssociateableUpdateRequest $request): Associateable
    {
        return $this->saveFromRequest($associateable, $request);
    }

    private function saveFromRequest(Associateable $associateable, FormRequest $request): Associateable
    {
        $associateable->forceFill([
            'user_id' => $request->input('user_id'),
            'associateable_type' => $request->input('associateable_type'),
            'associateable_id' => $request->input('associateable_id'),
        ])->save();
        return $associateable;
    }
}
