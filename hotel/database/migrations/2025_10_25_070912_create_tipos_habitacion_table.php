<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_tipos_habitacion_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposHabitacionTable extends Migration
{
    public function up()
    {
        Schema::create('tipos_habitacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // sencilla, doble, suite
            $table->text('descripcion');
            $table->integer('capacidad');
            $table->decimal('precio_base', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipos_habitacion');
    }
}