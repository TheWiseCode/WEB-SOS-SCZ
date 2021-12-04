<?php

namespace Database\Seeders;

use App\Models\Operator;
use App\Models\User;
use Illuminate\Database\Seeder;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $operators = $users->where('type', '=', '2')->sortDesc()->pluck('id');


        for($i = $operators->count(); $i>0; $i--){
            Operator::factory()->count(1)->create(['user_id' => $operators->pop()]);
        }
    }
}
