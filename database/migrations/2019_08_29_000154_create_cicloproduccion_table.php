<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCicloproduccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cicloproducciones', function (Blueprint $table) {
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
        Schema::dropIfExists('cicloproducciones');
    }
}
