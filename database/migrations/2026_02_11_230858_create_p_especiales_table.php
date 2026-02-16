<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('p_especiales', function (Blueprint $table) {
            $table->integer('id_pespeciales', true);
            $table->integer('id_venta');
            $table->integer('id_dir')->nullable();
            $table->integer('id_clie')->nullable();
            $table->datetime('fecha_creacion')->useCurrent();
            $table->datetime('fecha_entrega')->nullable();
            $table->integer('status')->default(1);
            $table->foreign('id_venta')->references('id_venta')->on('venta');
            $table->foreign('id_dir')->references('id_dir')->on('direcciones');
            $table->foreign('id_clie')->references('id_clie')->on('clientes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('p_especiales');
    }
};