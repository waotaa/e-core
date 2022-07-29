<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\PartnershipCreateRequest;
use Vng\EvaCore\Http\Requests\PartnershipUpdateRequest;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Repositories\PartnershipRepositoryInterface;

class PartnershipRepository extends BaseRepository implements PartnershipRepositoryInterface
{
    use SoftDeletableRepository;

    public string $model = Partnership::class;

    public function new(): Partnership
    {
        return new $this->model;
    }

    public function create(PartnershipCreateRequest $request): Partnership
    {
        return $this->saveFromRequest($this->new(), $request);
    }

    public function update(Partnership $partnership, PartnershipUpdateRequest $request): Partnership
    {
        return $this->saveFromRequest($partnership, $request);
    }

    public function saveFromRequest(Partnership $partnership, FormRequest $request): Partnership
    {
        $partnership = $partnership->fill([
            'name' => $request->input('name'),
        ]);
        $partnership->save();
        return $partnership;
    }
}
