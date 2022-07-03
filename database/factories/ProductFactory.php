<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{

    protected $model = \App\Models\Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'desc' => $this->faker->sentence(),
            'how_to_use' => $this->faker->sentence(),
            'meta_keywords' => $this->faker->word(),
            'is_active' => $this->faker->randomElement([0, 1]),
            'is_popular' => $this->faker->randomElement([0, 1]),
            'vendor_code' => $this->faker->randomNumber(6, true),
            'rating' => $this->faker->numberBetween([1, 5]),
            'number_of_ratings' => 1
        ];
    }
}
