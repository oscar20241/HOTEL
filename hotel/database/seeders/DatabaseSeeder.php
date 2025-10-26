<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TiposHabitacionSeeder::class,
            HabitacionesSeeder::class,
            UsersSeeder::class, // ← Asegúrate de que esté aquí
        ]);
    }
}po