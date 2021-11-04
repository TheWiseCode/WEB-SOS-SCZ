<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkShiftLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_shift_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_shift_id');
            $table->string('longitude'); //TODO: TRY TO JOIN THESE ATTRIBUTES IN A POINT ONE TO BE A COORDINATE
            $table->string('latitude');
            $table->dateTime('date_time');
            $table->timestamps();

            $table->foreign('work_shift_id')
                ->references('id')
                ->on('work_shifts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_shift_locations');
    }
}
