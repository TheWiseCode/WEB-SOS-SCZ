<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('officer_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('schedule_id');
            $table->dateTime('shift_starts');
            $table->dateTime('shift_end');
            $table->timestamps();

            $table->foreign('officer_id')
                ->references('id')
                ->on('officers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('schedule_id')
                ->references('id')
                ->on('schedules')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_shifts');
    }
}
