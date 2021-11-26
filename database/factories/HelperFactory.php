<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HelperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'longitude' => $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),
        ];
    }
}
