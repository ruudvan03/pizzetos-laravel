<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p_domicilio', function (Blueprint $table) {
            $table->integer('id_pdomicilio', true);
            $table->integer('id_clie');
            $table->integer('id_dir');
            $table->integer('id_venta');
            $table->integer('status')->default(1);
            $table->foreign('id_clie')->references('id_clie')->on('clientes');
            $table->foreign('id_dir')->references('id_dir')->on('direcciones');
            $table->foreign('id_venta')->references('id_venta')->on('venta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p_domicilio');
    }
};