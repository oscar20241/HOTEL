<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;

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
                return app(AdminUserController::class)->index();
            }

            if ($user->esRecepcionista()) {
                return view('Recepcionista');
            }

            return view('Huesped');
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
}
