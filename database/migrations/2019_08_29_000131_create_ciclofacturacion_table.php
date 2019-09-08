<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCiclofacturacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciclofacturaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('dias');
            $table->dateTime('inicio')->nullable();
            $table->dateTime('fin')->nullable();
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
        Schema::dropIfExists('ciclofacturaciones');
    }
}
