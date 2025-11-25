<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestPortalController extends Controller
{
    /**
     * Display the guest dashboard with upcoming stays and reservations.
     */
    public function index(Request $request)
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

        $tiposHabitacion = TipoHabitacion::with(['habitaciones' => function ($query) {
            $query->with(['imagenPrincipal', 'imagenes'])->orderBy('numero');
        }, 'tarifasDinamicas'])
            ->orderBy('precio_base')
            ->get();

        $habitaciones = Habitacion::with(['tipoHabitacion', 'imagenPrincipal', 'imagenes'])
            ->orderBy('numero')
            ->get();

        $tipoPreferidoId = null;
        $porPagina = (int) $request->input('por_pagina', 10);
        $porPagina = in_array($porPagina, [10, 15]) ? $porPagina : 10;

        $reservacionesQuery = $user->reservaciones()
            ->sinPendientesExpiradas()
            ->with([
                'habitacion.tipoHabitacion',
                'habitacion.imagenPrincipal',
                'pagos' => fn ($query) => $query->where('estado', 'completado'),
            ])
            ->orderByDesc('fecha_entrada');

        $proximaReservacion = (clone $reservacionesQuery)
            ->whereIn('estado', ['pendiente', 'confirmada', 'activa'])
            ->whereDate('fecha_salida', '>=', now()->toDateString())
            ->orderBy('fecha_entrada')
            ->first();

        $reservaciones = $reservacionesQuery
            ->paginate($porPagina)
            ->withQueryString()
            ->fragment('mis-reservas');

        return view('public.huesped.dashboard', [
            'habitaciones' => $habitaciones,
            'tiposHabitacion' => $tiposHabitacion,
            'reservaciones' => $reservaciones,
            'proximaReservacion' => $proximaReservacion,
            'tipoPreferidoId' => $tipoPreferidoId,
        ]);
    }
}
