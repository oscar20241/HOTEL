<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\HabitacionImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HabitacionController extends Controller
{
    // Mostrar listado de habitaciones
    public function index()
    {
        $habitaciones = Habitacion::with(['tipoHabitacion', 'imagenes'])
            ->orderBy('numero')
            ->get();
        
        return view('gerente.habitaciones', compact('habitaciones'));
    }

    // Mostrar formulario para crear habitación
    public function create()
    {
        $tiposHabitacion = TipoHabitacion::all();
        return view('gerente.crear-habitacion', compact('tiposHabitacion'));
    }

    // Guardar nueva habitación
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero' => 'required|string|unique:habitaciones|max:10',
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
            'estado' => 'required|in:disponible,ocupada,mantenimiento,limpieza',
            'capacidad' => 'required|integer|min:1|max:10',
            'caracteristicas' => 'nullable|string|max:500',
            'amenidades' => 'nullable|array',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
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

        // Procesar imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                $path = $imagen->store('habitaciones', 'public');
                
                HabitacionImagen::create([
                    'habitacion_id' => $habitacion->id,
                    'ruta_imagen' => $path,
                    'nombre_original' => $imagen->getClientOriginalName(),
                    'es_principal' => $index === 0, // La primera imagen es principal
                    'orden' => $index
                ]);
            }
        }

        return redirect()->route('gerente.habitaciones')
            ->with('success', 'Habitación creada exitosamente.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $habitacion = Habitacion::with('imagenes')->findOrFail($id);
        $tiposHabitacion = TipoHabitacion::all();
        
        return view('gerente.editar-habitacion', compact('habitacion', 'tiposHabitacion'));
    }

    // Actualizar habitación
    public function update(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'numero' => 'required|string|max:10|unique:habitaciones,numero,' . $habitacion->id,
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
            'estado' => 'required|in:disponible,ocupada,mantenimiento,limpieza',
            'capacidad' => 'required|integer|min:1|max:10',
            'caracteristicas' => 'nullable|string|max:500',
            'amenidades' => 'nullable|array',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Actualizar habitación
        $habitacion->update([
            'numero' => $request->numero,
            'tipo_habitacion_id' => $request->tipo_habitacion_id,
            'estado' => $request->estado,
            'capacidad' => $request->capacidad,
            'caracteristicas' => $request->caracteristicas,
            'amenidades' => $request->amenidades ?? []
        ]);

        // Procesar nuevas imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $index => $imagen) {
                $path = $imagen->store('habitaciones', 'public');
                
                HabitacionImagen::create([
                    'habitacion_id' => $habitacion->id,
                    'ruta_imagen' => $path,
                    'nombre_original' => $imagen->getClientOriginalName(),
                    'es_principal' => false,
                    'orden' => $habitacion->imagenes()->count() + $index
                ]);
            }
        }

        return redirect()->route('gerente.habitaciones')
            ->with('success', 'Habitación actualizada exitosamente.');
    }

    // Eliminar habitación
    public function destroy($id)
    {
        $habitacion = Habitacion::findOrFail($id);

        // Verificar si tiene reservaciones activas
        if ($habitacion->reservaciones()->whereIn('estado', ['confirmada', 'activa'])->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar una habitación con reservaciones activas.');
        }

        // Eliminar imágenes del storage
        foreach ($habitacion->imagenes as $imagen) {
            Storage::disk('public')->delete($imagen->ruta_imagen);
            $imagen->delete();
        }

        $habitacion->delete();

        return redirect()->route('gerente.habitaciones')
            ->with('success', 'Habitación eliminada exitosamente.');
    }

    // Eliminar imagen individual
    public function eliminarImagen($id)
    {
        $imagen = HabitacionImagen::findOrFail($id);
        
        // Eliminar archivo físico
        Storage::disk('public')->delete($imagen->ruta_imagen);
        
        $imagen->delete();

        return response()->json(['success' => 'Imagen eliminada correctamente.']);
    }

    // Marcar imagen como principal
    public function marcarPrincipal($id)
    {
        $imagen = HabitacionImagen::findOrFail($id);
        
        // Quitar principal de todas las imágenes de esta habitación
        HabitacionImagen::where('habitacion_id', $imagen->habitacion_id)
            ->update(['es_principal' => false]);
        
        // Marcar esta imagen como principal
        $imagen->update(['es_principal' => true]);

        return response()->json(['success' => 'Imagen principal actualizada.']);
    }
}