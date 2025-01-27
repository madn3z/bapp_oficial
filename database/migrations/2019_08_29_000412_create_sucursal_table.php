<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSucursalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('nombre');
            $table->string('direccion');
            $table->string('pais');
            $table->string('ciudad');
            $table->string('comuna');
            $table->integer('numeracion');
            $table->boolean('matriz');
            $table->decimal('lat', 10 , 5);
            $table->decimal('lng', 10 , 5);
            $table->timestamps();

            $table->foreign('empresa_id')
            ->references('id')
            ->on('empresas')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursales');
    }
}
