<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tamanos_refrescos', function (Blueprint $table) {
            $table->integer('id_tamano', true);
            $table->string('tamano', 50);
            $table->decimal('precio', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tamanos_refrescos');
    }
};