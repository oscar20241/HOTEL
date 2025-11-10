<?php
// database/migrations/2025_10_25_070943_create_reservaciones_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservacionesTable extends Migration
{
    public function up()
    {
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_reserva')->unique();

            // FK correctas
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('habitacion_id')->constrained('habitaciones')->cascadeOnDelete();

            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->integer('numero_huespedes');
            $table->enum('estado', ['pendiente', 'confirmada', 'activa', 'completada', 'cancelada'])->default('pendiente');
            $table->decimal('precio_total', 10, 2);
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservaciones');
    }
}
