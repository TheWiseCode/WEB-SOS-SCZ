<?php

namespace Database\Seeders;

use App\Models\Helper;
use App\Models\User;
use Illuminate\Database\Seeder;

class HelperSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $helpers = $users->where('type', '=', '3')->sortDesc()->pluck('id');


        for($i = $helpers->count(); $i>0; $i--){
            Helper::factory()->count(1)->create(
                [
                    'user_id' => $helpers->pop(),
                ]);
        }
    }
}
