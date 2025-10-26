<?php
<<<<<<< HEAD
// database/seeders/DatabaseSeeder.php
=======
>>>>>>> d3a78b76a17d842439eea092664b7c7eb0f5309e
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TiposHabitacionSeeder::class,
            HabitacionesSeeder::class,
<<<<<<< HEAD
            UsersSeeder::class,
=======
            UsersSeeder::class, // ← Asegúrate de que esté aquí
>>>>>>> d3a78b76a17d842439eea092664b7c7eb0f5309e
        ]);
    }
}