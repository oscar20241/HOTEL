<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapacidadAndAmenidadesToHabitacionesTable extends Migration
{
    public function up()
    {
        Schema::table('habitaciones', function (Blueprint $table) {
            $table->integer('capacidad')->default(2)->after('estado');
            $table->json('amenidades')->nullable()->after('caracteristicas');
        });
    }

    public function down()
    {
        Schema::table('habitaciones', function (Blueprint $table) {
            $table->dropColumn(['capacidad', 'amenidades']);
        });
    }
}