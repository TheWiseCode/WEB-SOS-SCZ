<?php

namespace Database\Seeders;

use App\Models\Civilian;
use App\Models\Emergency;
use Illuminate\Database\Seeder;

class EmergencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $civilians = Civilian::all()->sortDesc()->pluck('id');

        for($i = $civilians->count();$i > 0; $i--){
            Emergency::factory()->count(1)->create([
                'civilian_id' => $civilians->pop(),
            ]);
        }

    }
}
