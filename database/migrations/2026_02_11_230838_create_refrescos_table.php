<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refrescos', function (Blueprint $table) {
            $table->integer('id_refresco', true);
            $table->string('nombre', 255);
            $table->integer('id_tamano');
            $table->integer('id_cat');
            $table->foreign('id_tamano')->references('id_tamano')->on('tamanos_refrescos');
            $table->foreign('id_cat')->references('id_cat')->on('categorias_prod');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refrescos');
    }
};