<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            TiposHabitacionSeeder::class,
            HabitacionesSeeder::class,
            UsersSeeder::class,
        ]);
    }
}