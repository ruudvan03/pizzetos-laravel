<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursal', function (Blueprint $table) {
            $table->integer('id_suc', true); // Clave primaria
            $table->string('nombre', 255);
            $table->string('direccion', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sucursal');
    }
};