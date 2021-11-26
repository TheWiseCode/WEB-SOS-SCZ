<?php

namespace Database\Seeders;

use App\Models\Civilian;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {



         \App\Models\User::factory(100)->create();
         $this->call([
             CivilianSeeder::class,
             OperatorSeeder::class,
             HelperSeeder::class
         ]);



/*
        $users = User::all();
        $civilians = $users->where('type', '=', '1')->sortDesc()->pluck('id');


        for($i = $civilians->count(); $i>0; $i--){
            Civilian::factory()->count(1)->create(['user_id' => $civilians->pop()]);
        }
*/

    }
}
