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
            $table->string('type'); //1= paramedica, 2= policial, 3=incendiaria
            $table->string('description');
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
            $table->string('state')->default('1'); //1=:pendiente; 2=en curso; 3=finalizada
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->unsignedBigInteger('helper_id')->nullable();


            $table->foreignId('civilian_id')->references('id')->on('civilians')->cascadeOnUpdate();
            $table->foreign('operator_id')->references('id')->on('operators')->cascadeOnUpdate();
            $table->foreign('helper_id')->references('id')->on('helpers')->cascadeOnUpdate();
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
