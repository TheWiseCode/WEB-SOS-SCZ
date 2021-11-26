<?php

namespace Database\Seeders;

use App\Models\Civilian;
use App\Models\Helper;
use App\Models\WorkShift;
use Illuminate\Database\Seeder;

class WorkShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $helper = Helper::all()->sortDesc()->pluck('id');

        for($id = $helper->count(); $id>0; $id--){
            WorkShift::factory()->count(1)->create([
                'helper_id' => $helper->pop(),
            ]);
        }
    }
}
