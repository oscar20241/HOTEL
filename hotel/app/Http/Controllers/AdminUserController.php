<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\HabitacionImagen;
use App\Models\TarifaDinamica;
use Carbon\Carbon;
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
    
    // Obtener habitaciones para el dashboard con sus imágenes
    $habitaciones = Habitacion::with(['tipoHabitacion.tarifasDinamicas' => function ($query) {
            $query->orderBy('fecha_inicio');
        }, 'imagenes'])
        ->orderBy('numero')
        ->get();

    $tiposHabitacion = TipoHabitacion::with(['tarifasDinamicas' => function ($query) {
            $query->orderBy('fecha_inicio');
        }])->orderBy('nombre')->get();

    $tarifasDinamicas = TarifaDinamica::with('tipoHabitacion')
        ->orderBy('fecha_inicio')
        ->get();

    return view('Gerente', compact('huespedes', 'empleados', 'habitaciones', 'tiposHabitacion', 'tarifasDinamicas'));
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
        $habitacion = Habitacion::with(['tipoHabitacion', 'imagenes'])->findOrFail($id);

        return response()->json([
            'numero' => $habitacion->numero,
            'tipo_habitacion_id' => $habitacion->tipo_habitacion_id,
            'capacidad' => $habitacion->capacidad,
            'estado' => $habitacion->estado,
            'caracteristicas' => $habitacion->caracteristicas,
            'amenidades' => $habitacion->amenidades ?? [],
            'precio_actual' => $habitacion->precio_actual,
            'imagenes' => $habitacion->imagenes->map(function ($imagen) {
                return [
                    'id' => $imagen->id,
                    'url' => Storage::url($imagen->ruta_imagen),
                    'nombre' => $imagen->nombre_original,
                    'es_principal' => $imagen->es_principal,
                ];
            })
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
        'imagenes' => 'nullable|array',
        'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096'
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

    if ($request->hasFile('imagenes')) {
        foreach ($request->file('imagenes') as $index => $imagen) {
            $path = $imagen->store('habitaciones', 'public');

            HabitacionImagen::create([
                'habitacion_id' => $habitacion->id,
                'ruta_imagen' => $path,
                'nombre_original' => $imagen->getClientOriginalName(),
                'es_principal' => $index === 0,
                'orden' => $index
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Habitación creada exitosamente.',
        'habitacion' => $habitacion->load(['tipoHabitacion.tarifasDinamicas', 'imagenes'])
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
            'amenidades' => 'nullable|array',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096'
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

        if ($request->hasFile('imagenes')) {
            $ordenBase = $habitacion->imagenes()->count();

            foreach ($request->file('imagenes') as $index => $imagen) {
                $path = $imagen->store('habitaciones', 'public');

                HabitacionImagen::create([
                    'habitacion_id' => $habitacion->id,
                    'ruta_imagen' => $path,
                    'nombre_original' => $imagen->getClientOriginalName(),
                    'es_principal' => $habitacion->imagenes()->where('es_principal', true)->exists() ? false : $index === 0,
                    'orden' => $ordenBase + $index
                ]);
            }
        }

        if (!$habitacion->imagenes()->where('es_principal', true)->exists()) {
            $primeraImagen = $habitacion->imagenes()->orderBy('orden')->first();
            if ($primeraImagen) {
                $primeraImagen->update(['es_principal' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Habitación actualizada exitosamente.',
            'habitacion' => $habitacion->load(['tipoHabitacion.tarifasDinamicas', 'imagenes'])
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

        if ($habitacion->imagenes()->exists()) {
            foreach ($habitacion->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen->ruta_imagen);
                $imagen->delete();
            }
        }

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

    // =============================================
    // GESTIÓN DE TARIFAS DINÁMICAS
    // =============================================

    public function listTarifas()
    {
        $tarifas = TarifaDinamica::with('tipoHabitacion')
            ->orderBy('fecha_inicio')
            ->get()
            ->map(function ($tarifa) {
                return [
                    'id' => $tarifa->id,
                    'tipo_habitacion_id' => $tarifa->tipo_habitacion_id,
                    'tipo_habitacion' => $tarifa->tipoHabitacion->nombre,
                    'fecha_inicio' => $tarifa->fecha_inicio->toDateString(),
                    'fecha_fin' => $tarifa->fecha_fin->toDateString(),
                    'precio_modificado' => (float) $tarifa->precio_modificado,
                    'tipo_temporada' => $tarifa->tipo_temporada,
                    'descripcion' => $tarifa->descripcion,
                ];
            });

        return response()->json([
            'success' => true,
            'tarifas' => $tarifas,
        ]);
    }

    public function showTarifa($id)
    {
        $tarifa = TarifaDinamica::with('tipoHabitacion')->findOrFail($id);

        return response()->json([
            'id' => $tarifa->id,
            'tipo_habitacion_id' => $tarifa->tipo_habitacion_id,
            'fecha_inicio' => $tarifa->fecha_inicio->toDateString(),
            'fecha_fin' => $tarifa->fecha_fin->toDateString(),
            'precio_modificado' => (float) $tarifa->precio_modificado,
            'tipo_temporada' => $tarifa->tipo_temporada,
            'descripcion' => $tarifa->descripcion,
        ]);
    }

    public function storeTarifa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'precio_modificado' => 'required|numeric|min:0',
            'tipo_temporada' => 'required|in:alta,baja,especial',
            'descripcion' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($this->existeSolapamiento($request->tipo_habitacion_id, $request->fecha_inicio, $request->fecha_fin)) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una tarifa configurada para estas fechas en el mismo tipo de habitación.',
            ], 422);
        }

        $tarifa = TarifaDinamica::create([
            'tipo_habitacion_id' => $request->tipo_habitacion_id,
            'fecha_inicio' => Carbon::parse($request->fecha_inicio),
            'fecha_fin' => Carbon::parse($request->fecha_fin),
            'precio_modificado' => $request->precio_modificado,
            'tipo_temporada' => $request->tipo_temporada,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarifa dinámica creada correctamente.',
            'tarifa' => $tarifa->load('tipoHabitacion'),
        ], 201);
    }

    public function updateTarifa(Request $request, $id)
    {
        $tarifa = TarifaDinamica::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'precio_modificado' => 'required|numeric|min:0',
            'tipo_temporada' => 'required|in:alta,baja,especial',
            'descripcion' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($this->existeSolapamiento($request->tipo_habitacion_id, $request->fecha_inicio, $request->fecha_fin, $tarifa->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una tarifa configurada para estas fechas en el mismo tipo de habitación.',
            ], 422);
        }

        $tarifa->update([
            'tipo_habitacion_id' => $request->tipo_habitacion_id,
            'fecha_inicio' => Carbon::parse($request->fecha_inicio),
            'fecha_fin' => Carbon::parse($request->fecha_fin),
            'precio_modificado' => $request->precio_modificado,
            'tipo_temporada' => $request->tipo_temporada,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarifa dinámica actualizada correctamente.',
            'tarifa' => $tarifa->load('tipoHabitacion'),
        ]);
    }

    public function destroyTarifa($id)
    {
        $tarifa = TarifaDinamica::findOrFail($id);
        $tarifa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarifa dinámica eliminada correctamente.',
        ]);
    }

    private function existeSolapamiento($tipoHabitacionId, $fechaInicio, $fechaFin, $ignorarId = null)
    {
        return TarifaDinamica::where('tipo_habitacion_id', $tipoHabitacionId)
            ->when($ignorarId, function ($query) use ($ignorarId) {
                $query->where('id', '!=', $ignorarId);
            })
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                    ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
                    ->orWhere(function ($subquery) use ($fechaInicio, $fechaFin) {
                        $subquery->where('fecha_inicio', '<=', $fechaInicio)
                            ->where('fecha_fin', '>=', $fechaFin);
                    });
            })
            ->exists();
    }
}