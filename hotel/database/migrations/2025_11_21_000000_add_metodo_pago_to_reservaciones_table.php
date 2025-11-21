<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('reservaciones', 'metodo_pago')) {
            return;
        }

        Schema::table('reservaciones', function (Blueprint $table) {
            $table->enum('metodo_pago', ['pendiente', 'efectivo', 'tarjeta', 'transferencia', 'paypal'])
                ->default('pendiente')
                ->after('notas');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('reservaciones', 'metodo_pago')) {
            return;
        }

        Schema::table('reservaciones', function (Blueprint $table) {
            $table->dropColumn('metodo_pago');
        });
    }
};
