<?php

namespace Vng\EvaCore\Repositories\Eloquent;

use Illuminate\Foundation\Http\FormRequest;
use Vng\EvaCore\Http\Requests\ImplementationCreateRequest;
use Vng\EvaCore\Http\Requests\ImplementationUpdateRequest;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Repositories\ImplementationRepositoryInterface;

class ImplementationRepository extends BaseRepository implements ImplementationRepositoryInterface
{
    public string $model = Implementation::class;

    public function create(ImplementationCreateRequest $request): Implementation
    {
        return $this->saveFromRequest(new $this->model(), $request);
    }

    public function update(Implementation $implementation, ImplementationUpdateRequest $request): Implementation
    {
        return $this->saveFromRequest($implementation, $request);
    }

    public function saveFromRequest(Implementation $implementation, FormRequest $request): Implementation
    {
        $implementation->fill([
            'name' => $request->input('name'),
            'custom' => $request->input('custom'),
        ]);
        $implementation->save();
        return $implementation;
    }
}
