<?php
// database/seeders/HabitacionesSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabitacionesSeeder extends Seeder
{
    public function run()
    {
        // Habitaciones sencillas (101-110)
        for ($i = 101; $i <= 110; $i++) {
            DB::table('habitaciones')->insert([
                'numero' => $i,
                'tipo_habitacion_id' => 1, // Sencilla
                'estado' => 'disponible',
                'caracteristicas' => 'Vista al jardín, A/C, Wi-Fi',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Habitaciones dobles (201-210)
        for ($i = 201; $i <= 210; $i++) {
            DB::table('habitaciones')->insert([
                'numero' => $i,
                'tipo_habitacion_id' => 2, // Doble
                'estado' => 'disponible',
                'caracteristicas' => 'Vista a la piscina, A/C, Wi-Fi, Mini-frigobar',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Suites (301-305)
        for ($i = 301; $i <= 305; $i++) {
            DB::table('habitaciones')->insert([
                'numero' => $i,
                'tipo_habitacion_id' => 3, // Suite
                'estado' => 'disponible',
                'caracteristicas' => 'Vista al mar, Jacuzzi, Sala de estar, Servicio a la habitación',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}