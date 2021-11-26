<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmergencyUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['1','2','3','4']), // 1= a pie, 2= motocicleta, 3= vehiculo, 4= animal
            'vehicle_license' => $this->faker->randomNumber(8),
            'description' => $this->faker->text(100),
        ];
    }
}
