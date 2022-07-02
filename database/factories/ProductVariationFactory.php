<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariationFactory extends Factory
{

    protected $model = \App\Models\ProductVariation::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'color_id' => 0,
            'product_code' => $this->faker->randomNumber(6, true),
            'remainder' => intval($this->faker->randomNumber(3, true)) * 100,
            'price' => intval($this->faker->randomNumber(3, true)) * 1000,
            'discount_price' => null,
            'is_default' => $this->faker->randomElement([0, 1]),
            'is_available' => $this->faker->randomElement([0, 1]),
            'slug' => 'example-slug',
            'venkon_id' => 0,
            'is_active' => $this->faker->randomElement([0, 1])
        ];
    }
}
