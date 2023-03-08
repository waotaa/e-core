<?php

namespace Database\Factories;

use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\NewsItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsItemFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = NewsItem::class;

    public function definition()
    {
        return [
            'environment_id' => Environment::factory(),
            'title' => $this->faker->sentence,
            'sub_title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
        ];
    }

    public function forEnvironment(Environment $environment = null): Factory
    {
        return $this->for(
            $environment ?? Environment::factory(), 'environment'
        );
    }
}
