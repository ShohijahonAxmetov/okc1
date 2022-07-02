<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{

    protected $model = \App\Models\Brand::class;
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
            'img' => $this->faker->image(null, 640, 480),
            'position' => $this->faker->randomDigitNot(0),
            'is_active' => $this->faker->randomElement([0, 1]),
            'link' => $this->faker->word().'.com',
            'venkon_id' => 0
        ];
    }
}
