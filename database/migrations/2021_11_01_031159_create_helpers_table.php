<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpers', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('rank')->nullable();
<<<<<<< HEAD
            $table->string('emergency_unit');
            $table->boolean('in_turn')->default(false);
=======
            $table->boolean('in_turn')->default('false');
            $table->double('longitude')->nullable();
            $table->double('latitude')->nullable();
>>>>>>> f8e56ceb468a81bdf97c1f22422b8c52f4c51ff3
            $table->foreignId('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('helpers');
    }
}
