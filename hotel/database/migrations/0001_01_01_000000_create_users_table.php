<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
<<<<<<< HEAD
            $table->string('telefono')->nullable(); // ← AGREGADO
            $table->string('direccion')->nullable(); // ← AGREGADO
            $table->date('fecha_nacimiento')->nullable(); // ← AGREGADO
=======
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->date('fecha_nacimiento')->nullable();
>>>>>>> d3a78b76a17d842439eea092664b7c7eb0f5309e
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};