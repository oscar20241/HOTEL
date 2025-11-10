<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class RegistroController extends Controller
{
    // Muestra el formulario de registro
    public function create()
    {
        return view('Registro'); // Ajusta esto al nombre de tu vista
    }

    // Procesa el formulario de registro
    public function store(Request $request)
    {
        $fechaMinima = Carbon::now()->subYears(18)->toDateString();

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:' . $fechaMinima],
        ], [
            'fecha_nacimiento.before_or_equal' => 'Debes ser mayor de edad para registrarte.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
        ]);

        // Si la validación falla, regresa con errores
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear el usuario en la base de datos
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);

        // Redirigir al login con mensaje de éxito
       return redirect('/') // Redirige a la URL raíz
        ->with('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');
    }
}