<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->integer('id_metpago', true);
            $table->string('metodo', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
};