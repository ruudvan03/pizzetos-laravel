<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magno', function (Blueprint $table) {
            $table->integer('id_magno', true);
            $table->integer('id_especialidad');
            $table->integer('id_refresco');
            $table->decimal('precio', 10, 2);
            $table->foreign('id_especialidad')->references('id_esp')->on('especialidades');
            $table->foreign('id_refresco')->references('id_refresco')->on('refrescos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magno');
    }
};