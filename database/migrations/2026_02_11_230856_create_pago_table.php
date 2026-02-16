<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->integer('id_pago', true);
            $table->integer('id_venta');
            $table->integer('id_metpago');
            $table->decimal('monto', 10, 2);
            $table->string('referencia', 100)->nullable();
            $table->foreign('id_venta')->references('id_venta')->on('venta');
            $table->foreign('id_metpago')->references('id_metpago')->on('metodos_pago');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};