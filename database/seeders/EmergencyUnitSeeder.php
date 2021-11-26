<?php

namespace Database\Seeders;

use App\Models\EmergencyUnit;
use App\Models\Helper;
use Illuminate\Database\Seeder;

class EmergencyUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $helpers = Helper::all()->sortDesc()->pluck('id');


        for($i = $helpers->count(); $i>0; $i-- ){
            EmergencyUnit::factory()->count(1)->create([
                'helper_id' => $helpers->pop(),
            ]);
        }

    }
}
