<?php
// database/migrations/2025_10_29_073708_create_habitacion_imagenes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitacionImagenesTable extends Migration
{
    public function up()
    {
        Schema::create('habitacion_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habitacion_id')->constrained('habitaciones')->cascadeOnDelete();
            $table->string('ruta_imagen');
            $table->string('nombre_original');
            $table->boolean('es_principal')->default(false);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('habitacion_imagenes');
    }
}