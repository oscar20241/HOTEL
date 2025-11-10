<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Reservacion;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservacionController extends Controller
{
    /**
     * Store a newly created reservation for the authenticated guest.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'habitacion_id'     => ['required', 'exists:habitaciones,id'],
            'fecha_entrada'     => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'      => ['required', 'date', 'after:fecha_entrada'],
            'numero_huespedes'  => ['required', 'integer', 'min:1'],
            'notas'             => ['nullable', 'string', 'max:500'],
        ]);

        $habitacion = Habitacion::with('tipoHabitacion')->findOrFail($validated['habitacion_id']);

        // ❌ Mantenimiento
        if ($habitacion->estado === 'mantenimiento') {
            return back()->withInput()
                ->withErrors(['habitacion_id' => 'La habitación está en mantenimiento.']);
        }

        // ✅ Capacidad
        if ($validated['numero_huespedes'] > $habitacion->capacidad) {
            return back()->withInput()
                ->withErrors(['numero_huespedes' => "Máximo {$habitacion->capacidad} personas para esta habitación."]); 
        }

        // Normaliza fechas
        $fechaEntrada = Carbon::parse($validated['fecha_entrada'])->startOfDay();
        $fechaSalida  = Carbon::parse($validated['fecha_salida'])->startOfDay();

        // ✅ Disponibilidad (sin traslapes)
        if (!$habitacion->estaDisponible($fechaEntrada, $fechaSalida)) {
            return back()->withInput()
                ->withErrors(['habitacion_id' => 'Lo sentimos, la habitación ya está reservada para esas fechas.']);
        }

        $noches = $fechaEntrada->diffInDays($fechaSalida);
        if ($noches < 1) {
            return back()->withInput()
                ->withErrors(['fecha_salida' => 'Debes reservar al menos una noche.']);
        }

        $precioTotal = $noches * $habitacion->precio_actual;

        Reservacion::create([
            'user_id'          => $request->user()->id,
            'habitacion_id'    => $habitacion->id,
            'fecha_entrada'    => $fechaEntrada,
            'fecha_salida'     => $fechaSalida,
            'numero_huespedes' => $validated['numero_huespedes'],
            'estado'           => 'pendiente',
            'precio_total'     => $precioTotal,
            'notas'            => $validated['notas'] ?? null,
        ]);

        return redirect()->route('huesped.dashboard')
            ->with('success', "¡Reservación registrada por {$noches} noche(s) para {$validated['numero_huespedes']} huésped(es)!");
    }


    /**
     * Cancel a reservation that belongs to the authenticated guest.
     */
    public function destroy(Request $request, Reservacion $reservacion): RedirectResponse
    {
        if ($reservacion->user_id !== $request->user()->id) {
            abort(403);
        }

        if (!$reservacion->puedeCancelarse()) {
            return redirect()->back()->with('error', 'Esta reservación ya no puede cancelarse.');
        }

        $reservacion->update(['estado' => 'cancelada']);

        return redirect()->route('huesped.dashboard')->with('success', 'La reservación se canceló correctamente.');
    }







}

