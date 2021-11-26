<?php

namespace Database\Seeders;

use App\Models\Civilian;
use App\Models\User;
use Illuminate\Database\Seeder;

class CivilianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $civilians = $users->where('type', '=', '1')->sortDesc()->pluck('id');


        for($i = $civilians->count(); $i>0; $i--){
            Civilian::factory()->count(1)->create(['user_id' => $civilians->pop()]);
        }
    }
}
