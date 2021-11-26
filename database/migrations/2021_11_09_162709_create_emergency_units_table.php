<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_units', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 1= a pie, 2= motocicleta, 3= vehiculo, 4= animal
            $table->string('vehicle_license')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('helper_id')->references('id')->on('helpers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emergency_units');
    }
}
