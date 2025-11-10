<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Reservacion;
class PublicHabitacionController extends Controller
{
    /**
     * Display a listing of the rooms for guests.
     */
    public function index()
    {
        // Si es admin/gerente, mándalo a su panel.
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->esAdministrador() || $user->esGerente()) {
                return redirect()->route('gerente.dashboard');
            }
            // Recepcionista y Huésped se quedan en la portada pública.
        }

        $habitaciones = Habitacion::with(['tipoHabitacion', 'imagenPrincipal', 'imagenes'])
            ->orderBy('numero')
            ->get();

        // Siempre mostrar la portada pública con habitaciones.
        return view('public.habitaciones.index', compact('habitaciones'));
    }

    /**
     * Display the specified room details.
     */
    public function show(Habitacion $habitacion)
    {
        if (auth()->check() && (auth()->user()->esAdministrador() || auth()->user()->esGerente())) {
            return redirect()->route('gerente.dashboard');
        }

        $habitacion->load(['tipoHabitacion', 'imagenes']);

        return view('public.habitaciones.show', [
            'habitacion' => $habitacion,
        ]);
    }





public function disponibilidad(Habitacion $habitacion)
{
    // Si está en mantenimiento, bloquea un rango amplio (ej. próximos 180 días)
    $bloques = [];
    if ($habitacion->estado === 'mantenimiento') {
        $bloques[] = [
            'from' => now()->toDateString(),
            'to'   => now()->addDays(180)->toDateString(),
            'type' => 'mantenimiento'
        ];
    }

    // Rango de interés (por ejemplo, próximos 12 meses)
    $desde = now()->startOfDay();
    $hasta = now()->addYear()->startOfDay();

    $reservas = $habitacion->reservaciones()
        ->whereIn('estado', ['pendiente','confirmada','activa'])
        ->whereBetween('fecha_entrada', [$desde->copy()->subYear(), $hasta]) // algo generoso
        ->get(['fecha_entrada','fecha_salida','estado']);

    foreach ($reservas as $r) {
        $bloques[] = [
            'from' => $r->fecha_entrada->toDateString(),
            'to'   => $r->fecha_salida->toDateString(),
            'type' => 'ocupada'
        ];
    }

    return response()->json([
        'capacidad' => (int) $habitacion->capacidad,
        'bloques'   => $bloques, // [{from:'YYYY-MM-DD', to:'YYYY-MM-DD', type:'ocupada|mantenimiento'}]
    ]);
}




}
