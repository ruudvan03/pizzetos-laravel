<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->integer('id_gastos', true);
            $table->integer('id_suc');
            $table->string('descripcion', 255);
            $table->decimal('precio', 10, 2);
            $table->datetime('fecha')->useCurrent();
            $table->boolean('evaluado')->nullable();
            $table->integer('id_caja');
            $table->foreign('id_suc')->references('id_suc')->on('sucursal');
            $table->foreign('id_caja')->references('id_caja')->on('caja');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};