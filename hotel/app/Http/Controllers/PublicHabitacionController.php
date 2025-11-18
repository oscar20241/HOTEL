<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Illuminate\Http\Request;

class PublicHabitacionController extends Controller
{
    /**
     * Display a listing of the rooms for guests.
     */
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->esAdministrador() || $user->esGerente()) {
                return redirect()->route('gerente.dashboard');
            }
        }

        $habitaciones = Habitacion::with(['tipoHabitacion', 'imagenPrincipal', 'imagenes'])
            ->orderBy('numero')
            ->get();

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

    /**
     * Return JSON availability blocks for the requested room.
     */
    public function disponibilidad(HttpRequest $request, Habitacion $habitacion)
    {
        $bloques = [];

        if ($habitacion->estado === 'mantenimiento') {
            $bloques[] = [
                'from' => now()->toDateString(),
                'to' => now()->addDays(180)->toDateString(),
                'estado' => 'mantenimiento',
            ];
        }

        $reservacionIgnorada = $request->query('exclude_reservacion');

        $reservas = $habitacion->reservaciones()
            ->whereIn('estado', ['pendiente', 'confirmada', 'activa'])
            ->when($reservacionIgnorada, function ($query) use ($reservacionIgnorada) {
                $query->where('id', '!=', $reservacionIgnorada);
            })
            ->orderBy('fecha_entrada')
            ->get(['fecha_entrada', 'fecha_salida', 'estado']);

        foreach ($reservas as $reserva) {
            $noches = $reserva->fecha_entrada->diffInDays($reserva->fecha_salida);

            if ($noches < 1) {
                continue;
            }

            $bloques[] = [
                'from' => $reserva->fecha_entrada->toDateString(),
                'to' => $reserva->fecha_salida->copy()->subDay()->toDateString(),
                'estado' => 'ocupada',
            ];
        }

        return response()->json([
            'capacidad' => (int) $habitacion->capacidad,
            'bloques' => $bloques,
        ]);
    }
}
