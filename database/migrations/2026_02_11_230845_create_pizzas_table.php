<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pizzas', function (Blueprint $table) {
            $table->integer('id_pizza', true);
            $table->integer('id_esp');
            $table->integer('id_tamano');
            $table->integer('id_cat');
            $table->foreign('id_esp')->references('id_esp')->on('especialidades');
            $table->foreign('id_tamano')->references('id_tamañop')->on('tamanos_pizza');
            $table->foreign('id_cat')->references('id_cat')->on('categorias_prod');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pizzas');
    }
};