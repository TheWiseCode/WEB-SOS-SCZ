<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('helper_id');
            $table->double('longitude');
            $table->double('latitude');
            $table->date('date');
            $table->time('time');
            $table->timestamps();
            $table->foreign('helper_id')->references('id')->on('helpers')->cascadeOnDelete()
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
        Schema::dropIfExists('location_history');
    }
}
