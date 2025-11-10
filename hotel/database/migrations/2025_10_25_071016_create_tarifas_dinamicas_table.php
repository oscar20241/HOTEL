<?php
// database/migrations/2025_10_25_071016_create_tarifas_dinamicas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifasDinamicasTable extends Migration
{
    public function up()
    {
        Schema::create('tarifas_dinamicas', function (Blueprint $table) {
    $table->id();

$table->foreignId('tipo_habitacion_id')->constrained('tipos_habitacion');

    $table->date('fecha_inicio');
    $table->date('fecha_fin');
    $table->decimal('precio_modificado', 8, 2);
    $table->enum('tipo_temporada', ['alta', 'baja', 'especial']);
    $table->text('descripcion')->nullable();
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('tarifas_dinamicas');
    }
}