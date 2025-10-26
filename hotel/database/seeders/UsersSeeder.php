<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // LIMPIAR tablas primero para evitar duplicados
        DB::table('empleados')->delete();
        DB::table('users')->delete();

        // 1. Crear Administrador
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin Principal',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password'),
            'telefono' => '555-0001',
            'direccion' => 'Av. Principal #123',
            'fecha_nacimiento' => '1980-01-15',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('empleados')->insert([
            'user_id' => $adminId,
            'numero_empleado' => 'EMPADMIN001',
            'puesto' => 'administrador',
            'fecha_contratacion' => '2020-01-10',
            'salario' => 25000.00,
            'turno' => 'matutino',
            'estado' => 'activo',
            'observaciones' => 'Administrador principal del sistema',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Crear Recepcionista
        $recepcionistaId = DB::table('users')->insertGetId([
            'name' => 'Maria Recepcion',
            'email' => 'recepcion@hotel.com',
            'password' => Hash::make('password'),
            'telefono' => '555-0002',
            'direccion' => 'Calle Secundaria #456',
            'fecha_nacimiento' => '1990-05-20',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('empleados')->insert([
            'user_id' => $recepcionistaId,
            'numero_empleado' => 'EMPRECEP001',
            'puesto' => 'recepcionista',
            'fecha_contratacion' => '2022-03-15',
            'salario' => 12000.00,
            'turno' => 'mixto',
            'estado' => 'activo',
            'observaciones' => 'Recepcionista de turno matutino/vespertino',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Crear Huésped
        DB::table('users')->insert([
            'name' => 'Juan Huésped',
            'email' => 'huesped@ejemplo.com',
            'password' => Hash::make('password'),
            'telefono' => '555-0003',
            'direccion' => 'Av. Visitante #789',
            'fecha_nacimiento' => '1985-08-30',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}