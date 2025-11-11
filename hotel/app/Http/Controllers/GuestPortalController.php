<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\Support\Facades\Auth;

class GuestPortalController extends Controller
{
    /**
     * Display the guest dashboard with upcoming stays and reservations.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->esEmpleado()) {
            if ($user->esAdministrador() || $user->esGerente()) {
                return redirect()->route('gerente.dashboard');
            }

            return redirect()->route('home');
        }

        $habitaciones = Habitacion::with(['tipoHabitacion', 'imagenPrincipal'])
            ->orderBy('numero')
            ->get();

        $tiposHabitacion = TipoHabitacion::with(['habitaciones' => function ($query) {
                $query->with(['imagenPrincipal', 'imagenes'])->orderBy('numero');
            }, 'tarifasDinamicas'])
            ->orderBy('precio_base')
            ->get();

        $reservaciones = $user->reservaciones()
            ->with([
                'habitacion.tipoHabitacion',
                'habitacion.imagenPrincipal',
                'pagos' => fn ($query) => $query->where('estado', 'completado'),
            ])
            ->orderByDesc('fecha_entrada')
            ->get();

        $proximaReservacion = $reservaciones
            ->filter(fn ($reservacion) => in_array($reservacion->estado, ['pendiente', 'confirmada', 'activa'])
                && ($reservacion->fecha_salida->isFuture() || $reservacion->fecha_salida->isToday()))
            ->sortBy('fecha_entrada')
            ->first();

        return view('public.huesped.dashboard', [
            'habitaciones' => $habitaciones,
            'tiposHabitacion' => $tiposHabitacion,
            'reservaciones' => $reservaciones,
            'proximaReservacion' => $proximaReservacion,
        ]);
    }
}
