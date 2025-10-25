<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_habitaciones_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitacionesTable extends Migration
{
    public function up()
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('tipo_habitacion_id')->constrained('tipos_habitacion');
            $table->enum('estado', ['disponible', 'ocupada', 'mantenimiento', 'limpieza'])->default('disponible');
            $table->text('caracteristicas')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('habitaciones');
    }
}