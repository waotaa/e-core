<?php

namespace Vng\EvaCore\Services\ImExport;

use Illuminate\Database\Eloquent\Model;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\RegionalParty;

class RegionalPartyFromArrayService extends BaseFromArrayService
{
    public function handle(): ?Model
    {
        $data = $this->data;

        /** @var RegionalParty $regionalParty */
        $regionalParty = RegionalParty::query()->firstOrNew([
            'slug' => $data['slug'],
        ], [
            'name' => $data['name'],
        ]);
        $region = Region::query()->where('slug', $data['region']['slug'])->firstOrFail();
        $regionalParty->region()->associate($region);
        $regionalParty->save();
        return $regionalParty;
    }
}