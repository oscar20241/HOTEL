<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_empleados_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadosTable extends Migration
{
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('numero_empleado')->unique();
            $table->enum('puesto', ['recepcionista', 'administrador', 'limpieza', 'gerente']);
            $table->date('fecha_contratacion');
            $table->decimal('salario', 10, 2)->nullable();
            $table->enum('turno', ['matutino', 'vespertino', 'nocturno', 'mixto'])->default('matutino');
            $table->enum('estado', ['activo', 'inactivo', 'vacaciones', 'licencia'])->default('activo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}