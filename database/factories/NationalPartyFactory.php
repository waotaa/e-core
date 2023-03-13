<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Vng\EvaCore\Models\NationalParty;

class NationalPartyFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = NationalParty::class;

    public function definition()
    {
        return [
            'name' => 'Landelijke partij',
            'slug' => 'landelijke-partij',
        ];
    }
}

