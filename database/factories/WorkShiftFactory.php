<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'day_turn' => $this->faker->date('Y-m-d'),
            'start_turn' => $this->faker->time(),
            'end_turn' => $this->faker->time(),
        ];
    }
}
