<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_habitacion_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_habitacion_id')->constrained('tipos_habitacion')->onDelete('cascade');
            $table->string('ruta_imagen');
            $table->string('nombre_original')->nullable();
            $table->boolean('es_principal')->default(false);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_habitacion_imagenes');
    }
};
