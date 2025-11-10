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
            'habitacion_id' => ['required', 'exists:habitaciones,id'],
            'fecha_entrada' => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida' => ['required', 'date', 'after:fecha_entrada'],
            'numero_huespedes' => ['required', 'integer', 'min:1'],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $habitacion = Habitacion::with('tipoHabitacion')->findOrFail($validated['habitacion_id']);

        if ($validated['numero_huespedes'] > $habitacion->capacidad) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['numero_huespedes' => 'La habitación seleccionada no cuenta con capacidad suficiente.']);
        }

        $fechaEntrada = Carbon::parse($validated['fecha_entrada']);
        $fechaSalida = Carbon::parse($validated['fecha_salida']);

        if (!$habitacion->estaDisponible($fechaEntrada, $fechaSalida)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['habitacion_id' => 'Lo sentimos, la habitación ya está reservada para esas fechas.']);
        }

        $noches = $fechaEntrada->diffInDays($fechaSalida);

        if ($noches < 1) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['fecha_salida' => 'Debes reservar al menos una noche.']);
        }

        $precioTotal = $noches * $habitacion->precio_actual;

        Reservacion::create([
            'user_id' => $request->user()->id,
            'habitacion_id' => $habitacion->id,
            'fecha_entrada' => $fechaEntrada,
            'fecha_salida' => $fechaSalida,
            'numero_huespedes' => $validated['numero_huespedes'],
            'estado' => 'pendiente',
            'precio_total' => $precioTotal,
            'notas' => $validated['notas'] ?? null,
        ]);

        return redirect()->route('home')
            ->with('success', '¡Tu reservación fue registrada! Nuestro equipo te contactará para confirmar los detalles.');
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

        return redirect()->route('home')->with('success', 'La reservación se canceló correctamente.');
    }
}

