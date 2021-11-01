<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Rating::class;

    public function definition()
    {
        return [
            'general_score' => $this->faker->numberBetween(1,5),
            'general_explanation' => $this->faker->text(40),
            'result_score' => $this->faker->numberBetween(1,5),
            'result_explanation' => $this->faker->text(40),
            'execution_score' => $this->faker->numberBetween(1,5),
            'execution_explanation' => $this->faker->text(40),
            'author' => $this->faker->name,
        ];
    }
}
