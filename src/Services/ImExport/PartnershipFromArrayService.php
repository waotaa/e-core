<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Township;

class PartnershipFromArrayService extends BaseFromArrayService
{
    public function handle(): Model
    {
        $data = $this->data;

        /** @var Partnership $partnership */
        $partnership = Partnership::query()->firstOrNew([
            'slug' => $data['slug'],
        ], [
            'name' => $data['name'],
        ]);
        $partnership->save();

        $townshipSlugs = collect($data['townships'])->pluck('slug');
        $townships = Township::query()->whereIn('slug', $townshipSlugs)->get();
        $partnership->townships()->syncWithoutDetaching($townships);
        return $partnership;
    }
}