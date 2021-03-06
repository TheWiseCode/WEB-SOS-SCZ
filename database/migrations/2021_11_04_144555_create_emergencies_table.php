<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergencies', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['paramedic', 'police', 'fireman']);
            $table->string('description');
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->enum('state',['pending', 'progress', 'finalized'])->default('pending');
            $table->foreignId('civilian_id')->references('id')
                ->on('civilians');
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
        Schema::dropIfExists('emergencies');
    }
}
