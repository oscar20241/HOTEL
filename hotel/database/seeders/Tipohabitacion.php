<?php
// database/seeders/TiposHabitacionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposHabitacionSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipos_habitacion')->insert([
            [
                'nombre' => 'Sencilla',
                'descripcion' => 'Habitación individual con cama sencilla, baño privado y TV',
                'capacidad' => 1,
                'precio_base' => 500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Doble', 
                'descripcion' => 'Habitación con cama doble o dos camas individuales, baño privado y TV',
                'capacidad' => 2,
                'precio_base' => 800.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Suite',
                'descripcion' => 'Suite con sala separada, amenities premium y vista privilegiada',
                'capacidad' => 4,
                'precio_base' => 1500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}