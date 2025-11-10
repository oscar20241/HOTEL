<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_pagos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservacion_id')
      ->constrained('reservaciones')
      ->cascadeOnDelete();

            $table->decimal('monto', 10, 2);
            $table->enum('metodo_pago', ['tarjeta', 'efectivo', 'transferencia', 'paypal']);
            $table->enum('estado', ['pendiente', 'completado', 'fallido', 'reembolsado'])->default('pendiente');
            $table->string('referencia')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}