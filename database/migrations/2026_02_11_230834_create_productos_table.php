<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->integer('id_producto', true);
            // Agrega aquí los campos de tu tabla productos
            // Basado en tu BD, podría ser algo como:
            $table->string('nombre', 255);
            $table->decimal('precio', 10, 2);
            $table->integer('id_cat');
            $table->foreign('id_cat')->references('id_cat')->on('categorias_prod');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};