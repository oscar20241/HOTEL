<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Carbon\Carbon;

class PublicHabitacionController extends Controller
{
    /**
     * Display a listing of the rooms for guests.
     */
    public function index()
    {
        $habitaciones = Habitacion::with(['tipoHabitacion', 'imagenPrincipal', 'imagenes'])
            ->where('estado', '!=', 'mantenimiento')
            ->orderBy('numero')
            ->get();

        if (!auth()->check()) {
            return view('public.habitaciones.index', [
                'habitaciones' => $habitaciones,
                'reservaciones' => collect(),
                'proximaReservacion' => null,
            ]);
        }

        $user = auth()->user();

        if ($user->esAdministrador() || $user->esGerente()) {
            return app(AdminUserController::class)->index();
        }

        if ($user->esRecepcionista()) {
            return view('Recepcionista');
        }

        $reservaciones = $user->reservaciones()
            ->with(['habitacion.tipoHabitacion', 'habitacion.imagenPrincipal'])
            ->orderByDesc('fecha_entrada')
            ->get();

        $proximaReservacion = $reservaciones
            ->filter(function ($reservacion) {
                return in_array($reservacion->estado, ['pendiente', 'confirmada', 'activa'])
                    && $reservacion->fecha_entrada->greaterThanOrEqualTo(Carbon::today());
            })
            ->sortBy('fecha_entrada')
            ->first();

        return view('public.habitaciones.index', [
            'habitaciones' => $habitaciones,
            'reservaciones' => $reservaciones,
            'proximaReservacion' => $proximaReservacion,
        ]);
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
}
