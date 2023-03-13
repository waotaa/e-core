<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Partnership;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnershipFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Partnership::class;

    public function definition()
    {
        return [
            'name' => 'Samenwerkingsverband',
            'slug' => 'samenwerkingsverband',
        ];
    }
}
