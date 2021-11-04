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
            $table->unsignedBigInteger('citizen_id');
            $table->unsignedBigInteger('type_institution_id');
            $table->string('description');
            $table->string('location'); //TODO: It's supposed to be a coordinate
            $table->timestamps();

            $table->foreign('citizen_id')
                ->references('id')
                ->on('cityzens')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('type_institution_id')
                ->references('id')
                ->on('type_institutions')
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
        Schema::dropIfExists('emergencies');
    }
}
