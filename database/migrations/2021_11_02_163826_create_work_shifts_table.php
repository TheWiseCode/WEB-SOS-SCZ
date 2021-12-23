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
            $table->string('day_turn');
            $table->time('start_turn');
            $table->time('end_turn');
            $table->unsignedBigInteger('helper_id')->nullable();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->timestamps();

            $table->foreign('helper_id')->references('id')->on('helpers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('operator_id')->references('id')->on('operators')->cascadeOnUpdate()->cascadeOnDelete();
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
