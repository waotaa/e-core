<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\RegionalParty;

class RegionalPartyFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = RegionalParty::class;

    public function definition()
    {
        return [
            'name' => 'Regionale partij',
            'slug' => 'regionale-partij',
        ];
    }

    public function forRegion(Region $region = null): Factory
    {
        return $this->for($region ?? Region::factory(), 'region');
    }
}

