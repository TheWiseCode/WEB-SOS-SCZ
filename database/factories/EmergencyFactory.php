<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmergencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['1','2','3']),  //1= paramedica, 2= policial, 3=incendiaria
            'description' => $this->faker->text(100),
            'longitude' => $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),

        ];
    }
}
