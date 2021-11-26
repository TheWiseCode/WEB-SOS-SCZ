<?php

namespace Database\Factories;

use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CivilianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$users = \App\Models\User::all();
        //$user_id_collection = $users->where('type', '=', '1')->pluck('id');

        return [
            //'user_id' => $user_id_collection->pop(),
        ];
    }
}
