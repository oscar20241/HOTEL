<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_reservaciones_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateReservacionesTable extends Migration
{
    public function up()
    {
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_reserva')->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('habitacion_id')->constrained();
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