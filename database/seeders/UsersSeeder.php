<?php

namespace Database\Seeders;

use App\Models\Civilian;
use App\Models\Helper;
use App\Models\Operator;
use App\Models\User;
use App\Models\WorkShift;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $names = ['civ', 'ope', 'pol', 'fir', 'med'];
        $types = ['civilian', 'operator', 'helper', 'helper', 'helper'];
        for ($i = 0; $i < 50; $i++) {
            $mod = $i % 10;
            $ind = $i / 10;
            $name = $names[$ind];
            $type = $types[$ind];
            $user = User::create([
                'name' => $name . $mod,
                'last_name' => $faker->lastName(),
                'ci' => $faker->randomNumber(8),
                'home_address' => $faker->address(),
                'birthday' => $faker->date('Y-m-d'),
                'sex' => $faker->randomElement(['masculino', 'femenino', 'otro']),
                'cellphone' => $faker->unique()->phoneNumber(),
                'email' => $name . $mod . '@gmail.com',
                'email_verified_at' => now(),
                'type' => $type,
                'password' => Hash::make('123456'),
                'remember_token' => Str::random(10),
            ]);
        }
        $civilians = User::where('type', 'civilian')->get();
        foreach ($civilians as $it) {
            Civilian::create(['user_id' => $it->id]);
        }
        $operators = User::where('type', 'operator')->get();
        foreach ($operators as $it) {
            Operator::create(['user_id' => $it->id]);
        }
        $helpers = User::where('type', 'helper')->get();
        $types = ['pol' => 'police', 'fir' => 'fireman', 'med' => 'paramedic'];
        foreach ($helpers as $it) {
            $key = substr($it->name, 0, 3);
            $helper = Helper::create([
                'user_id' => $it->id,
                'type' => $types[$key],
                'rank' => $types[$key] == 'police' ? 'sargent': null,
                //'emergency_unit' => $data['emergency_unit']
            ]);
            WorkShift::create([
                'day_turn' => 'monday',
                'start_turn' => '10:00',
                'end_turn' => '18:00',
                'helper_id' => $helper->id
            ]);
        }
    }
}
