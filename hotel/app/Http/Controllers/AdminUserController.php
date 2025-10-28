<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // ✅ IMPORT AGREGADO

class AdminUserController extends Controller
{
    // Mostrar lista de todos los usuarios
    public function index()
    {
        $huespedes = User::whereDoesntHave('empleado')->get();
        
        // Separar correctamente los empleados por puesto
        $empleados = User::whereHas('empleado')
            ->with('empleado')
            ->get();
        
        return view('Gerente', compact('huespedes', 'empleados'));
    }

    // Mostrar formulario para crear empleado
    public function createEmpleado()
    {
        return view('admin.crear-empleado');
    }

    // Guardar nuevo empleado
    public function storeEmpleado(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'numero_empleado' => 'required|string|unique:empleados',
            'puesto' => 'required|string|in:recepcionista,administrador,limpieza,gerente',
            'fecha_contratacion' => 'required|date',
            'salario' => 'required|numeric|min:0',
            'turno' => 'required|in:matutino,vespertino,nocturno,mixto',
            'estado' => 'required|in:activo,inactivo,vacaciones,licencia',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);

        // Crear registro de empleado
        Empleado::create([
            'user_id' => $user->id,
            'numero_empleado' => $request->numero_empleado,
            'puesto' => $request->puesto,
            'fecha_contratacion' => $request->fecha_contratacion,
            'salario' => $request->salario,
            'turno' => $request->turno,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Empleado creado exitosamente.');
    }

    // Mostrar formulario de edición de empleado
    public function editEmpleado($id)
    {
        $user = User::with('empleado')->findOrFail($id);
        return view('admin.editar-empleado', compact('user'));
    }

    // Actualizar empleado
    public function updateEmpleado(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'numero_empleado' => [
                'required',
                'string',
                Rule::unique('empleados')->ignore($user->empleado->id)
            ],
            'puesto' => 'required|string|in:recepcionista,administrador,limpieza,gerente',
            'fecha_contratacion' => 'required|date',
            'salario' => 'required|numeric|min:0',
            'turno' => 'required|in:matutino,vespertino,nocturno,mixto',
            'estado' => 'required|in:activo,inactivo,vacaciones,licencia',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Actualizar usuario
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);

        // Actualizar empleado
        $user->empleado->update([
            'numero_empleado' => $request->numero_empleado,
            'puesto' => $request->puesto,
            'fecha_contratacion' => $request->fecha_contratacion,
            'salario' => $request->salario,
            'turno' => $request->turno,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Empleado actualizado exitosamente.');
    }

    // Eliminar usuario (tanto huésped como empleado)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevenir eliminación de gerentes
        if ($user->empleado && $user->empleado->puesto === 'gerente') {
            return redirect()->route('admin.usuarios')
                ->with('error', 'No se pueden eliminar cuentas de gerente.');
        }
        
        // Prevenir que un usuario se elimine a sí mismo
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.usuarios')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        
        $user->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado exitosamente.');
    }

    // Cambiar estado de empleado - ✅ VERSIÓN CORREGIDA
    public function cambiarEstado($id)
    {
        try {
            // Buscar el empleado por user_id
            $empleado = Empleado::where('user_id', $id)->firstOrFail();
            
            // Prevenir cambiar estado de gerentes
            if ($empleado->puesto === 'gerente') {
                return redirect()->back()->with('error', 'No se puede cambiar el estado de un gerente.');
            }
            
            // Prevenir que un usuario cambie su propio estado
            if ($empleado->user_id === Auth::id()) {
                return redirect()->back()->with('error', 'No puedes cambiar tu propio estado.');
            }
            
            // Cambiar estado
            $nuevoEstado = $empleado->estado === 'activo' ? 'inactivo' : 'activo';
            
            $empleado->update([
                'estado' => $nuevoEstado
            ]);
            
            $mensaje = $nuevoEstado === 'activo' 
                ? 'Empleado activado correctamente.' 
                : 'Empleado desactivado correctamente.';
                
            return redirect()->back()->with('success', $mensaje);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }
}