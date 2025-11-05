<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\HabitacionImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    // Mostrar dashboard del gerente CON HABITACIONES
public function index()
{
    $huespedes = User::whereDoesntHave('empleado')->get();
    
    // Separar correctamente los empleados por puesto
    $empleados = User::whereHas('empleado')
        ->with('empleado')
        ->get();
    
    // 🆕 Obtener habitaciones para el dashboard (SIN IMÁGENES TEMPORALMENTE)
    $habitaciones = Habitacion::with('tipoHabitacion') // Solo cargar tipoHabitacion
        ->orderBy('numero')
        ->get();
    
    return view('Gerente', compact('huespedes', 'empleados', 'habitaciones'));
}

    // =============================================
    // GESTIÓN DE EMPLEADOS (MÉTODOS EXISTENTES)
    // =============================================

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
            'numero_empleado' => 'nullable|string|unique:empleados',
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

    // Cambiar estado de empleado
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

    // =============================================
    // GESTIÓN DE HABITACIONES (NUEVOS MÉTODOS)
    // =============================================

    // 🆕 Obtener datos de una habitación para edición
    public function showHabitacion($id)
    {
        $habitacion = Habitacion::with('tipoHabitacion')->findOrFail($id);
        
        return response()->json([
            'numero' => $habitacion->numero,
            'tipo_habitacion_id' => $habitacion->tipo_habitacion_id,
            'capacidad' => $habitacion->capacidad,
            'estado' => $habitacion->estado,
            'caracteristicas' => $habitacion->caracteristicas,
            'amenidades' => $habitacion->amenidades ?? []
        ]);
    }

   // 🆕 Guardar nueva habitación (para el modal)
public function storeHabitacion(Request $request)
{
    $validator = Validator::make($request->all(), [
        'numero' => 'required|string|unique:habitaciones|max:10',
        'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
        'estado' => 'required|in:disponible,ocupada,mantenimiento,limpieza',
        'capacidad' => 'required|integer|min:1|max:10',
        'caracteristicas' => 'nullable|string|max:500',
        'amenidades' => 'nullable|array',
        // 'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Comentar temporalmente
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Crear habitación
    $habitacion = Habitacion::create([
        'numero' => $request->numero,
        'tipo_habitacion_id' => $request->tipo_habitacion_id,
        'estado' => $request->estado,
        'capacidad' => $request->capacidad,
        'caracteristicas' => $request->caracteristicas,
        'amenidades' => $request->amenidades ?? []
    ]);

    // Procesar imágenes (COMENTAR TEMPORALMENTE)
    // if ($request->hasFile('imagenes')) {
    //     foreach ($request->file('imagenes') as $index => $imagen) {
    //         $path = $imagen->store('habitaciones', 'public');
    //         
    //         HabitacionImagen::create([
    //             'habitacion_id' => $habitacion->id,
    //             'ruta_imagen' => $path,
    //             'nombre_original' => $imagen->getClientOriginalName(),
    //             'es_principal' => $index === 0,
    //             'orden' => $index
    //         ]);
    //     }
    // }

    return response()->json([
        'success' => true,
        'message' => 'Habitación creada exitosamente.',
        'habitacion' => $habitacion->load('tipoHabitacion') // Solo cargar tipoHabitacion
    ]);
}

    // 🆕 Actualizar habitación
    public function updateHabitacion(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'numero' => 'required|string|max:10|unique:habitaciones,numero,' . $habitacion->id,
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
            'estado' => 'required|in:disponible,ocupada,mantenimiento,limpieza',
            'capacidad' => 'required|integer|min:1|max:10',
            'caracteristicas' => 'nullable|string|max:500',
            'amenidades' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $habitacion->update([
            'numero' => $request->numero,
            'tipo_habitacion_id' => $request->tipo_habitacion_id,
            'estado' => $request->estado,
            'capacidad' => $request->capacidad,
            'caracteristicas' => $request->caracteristicas,
            'amenidades' => $request->amenidades ?? []
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Habitación actualizada exitosamente.',
            'habitacion' => $habitacion->load('tipoHabitacion')
        ]);
    }

    // 🆕 Eliminar habitación
    // 🆕 Eliminar habitación
public function destroyHabitacion($id)
{
    try {
        $habitacion = Habitacion::findOrFail($id);

        // Verificar si tiene reservaciones activas
        if ($habitacion->reservaciones()->whereIn('estado', ['confirmada', 'activa'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una habitación con reservaciones activas.'
            ], 422);
        }

        // Eliminar imágenes asociadas (COMENTAR TEMPORALMENTE)
        // if ($habitacion->imagenes()->exists()) {
        //     foreach ($habitacion->imagenes as $imagen) {
        //         Storage::disk('public')->delete($imagen->ruta_imagen);
        //         $imagen->delete();
        //     }
        // }

        $habitacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Habitación eliminada exitosamente.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la habitación: ' . $e->getMessage()
        ], 500);
    }
}
}