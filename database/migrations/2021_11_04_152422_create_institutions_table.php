<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_institution_id');
            $table->string('name');
            $table->string('description');
            $table->string('address');
            $table->string('location');//TODO: IT'S SUPPOSSED TO BE A COORDINATE
            $table->timestamps();

            $table->foreign('type_institution_id')
                ->references('id')
                ->on('type_institutions')
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
        Schema::dropIfExists('institutions');
    }
}
