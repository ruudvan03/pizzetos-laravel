<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barra', function (Blueprint $table) {
            $table->integer('id_barr', true);
            $table->integer('id_especialidad');
            $table->integer('id_cat');
            $table->decimal('precio', 10, 2);
            $table->foreign('id_especialidad')->references('id_esp')->on('especialidades');
            $table->foreign('id_cat')->references('id_cat')->on('categorias_prod');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barra');
    }
};