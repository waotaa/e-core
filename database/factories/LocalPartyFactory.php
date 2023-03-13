<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\Township;

class LocalPartyFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = LocalParty::class;

    public function definition()
    {
        return [
            'name' => 'Lokale partij',
            'slug' => 'lokale-partij',
        ];
    }

    public function forTownship(Township $township = null): Factory
    {
        return $this->for($township ?? Township::factory(), 'township');
    }
}

