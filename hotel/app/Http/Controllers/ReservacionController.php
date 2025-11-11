<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Reservacion;
use App\Models\TipoHabitacion;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class ReservacionController extends Controller
{
    /**
     * Store a newly created reservation for the authenticated guest.
     */
    public function store(HttpRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_habitacion_id' => ['nullable', 'exists:tipos_habitacion,id', 'required_without:habitacion_id'],
            'habitacion_id'     => ['nullable', 'exists:habitaciones,id', 'required_without:tipo_habitacion_id'],
            'fecha_entrada'     => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'      => ['required', 'date', 'after:fecha_entrada'],
            'numero_huespedes'  => ['required', 'integer', 'min:1'],
            'notas'             => ['nullable', 'string', 'max:500'],
        ]);

        // Normaliza fechas
        $fechaEntrada = Carbon::parse($validated['fecha_entrada'])->startOfDay();
        $fechaSalida  = Carbon::parse($validated['fecha_salida'])->startOfDay();

        $noches = $fechaEntrada->diffInDays($fechaSalida);
        if ($noches < 1) {
            return back()->withInput()
                ->withErrors(['fecha_salida' => 'Debes reservar al menos una noche.']);
        }

        if (!empty($validated['habitacion_id'])) {
            $habitacion = Habitacion::with('tipoHabitacion')->findOrFail($validated['habitacion_id']);

            if ($habitacion->estado === 'mantenimiento') {
                return back()->withInput()
                    ->withErrors(['habitacion_id' => 'La habitación está en mantenimiento.']);
            }

            if ($validated['numero_huespedes'] > $habitacion->capacidad) {
                return back()->withInput()
                    ->withErrors(['numero_huespedes' => "Máximo {$habitacion->capacidad} personas para esta habitación."]);
            }

            if (!$habitacion->estaDisponible($fechaEntrada, $fechaSalida)) {
                return back()->withInput()
                    ->withErrors(['habitacion_id' => 'Lo sentimos, la habitación ya está reservada para esas fechas.']);
            }
        } else {
            $tipo = TipoHabitacion::with(['habitaciones' => function ($query) {
                $query->with('tipoHabitacion');
            }])->findOrFail($validated['tipo_habitacion_id']);

            if ($validated['numero_huespedes'] > $tipo->capacidad) {
                return back()->withInput()
                    ->withErrors(['numero_huespedes' => "Máximo {$tipo->capacidad} personas para esta categoría."]);
            }

            $candidatas = $tipo->habitaciones
                ->filter(fn ($habitacion) => $habitacion->estado !== 'mantenimiento' && $habitacion->capacidad >= $validated['numero_huespedes']);

            if ($candidatas->isEmpty()) {
                return back()->withInput()
                    ->withErrors(['tipo_habitacion_id' => 'No hay habitaciones disponibles para esta categoría en este momento.']);
            }

            $disponibles = $candidatas->filter(fn ($habitacion) => $habitacion->estaDisponible($fechaEntrada, $fechaSalida));

            if ($disponibles->isEmpty()) {
                return back()->withInput()
                    ->withErrors(['tipo_habitacion_id' => "No hay habitaciones disponibles de tipo {$tipo->nombre} para las fechas seleccionadas."]);
            }

            $habitacion = $disponibles->random();
            $habitacion->loadMissing('tipoHabitacion');
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

        $mensaje = "¡Reservación registrada por {$noches} noche(s) para {$validated['numero_huespedes']} huésped(es)!";

        if (empty($validated['habitacion_id']) && isset($habitacion)) {
            $mensaje .= ' Se asignó la habitación ' . $habitacion->numero;
            if ($habitacion->tipoHabitacion?->nombre) {
                $mensaje .= ' (' . $habitacion->tipoHabitacion->nombre . ')';
            }
            $mensaje .= '.';
        }

        return redirect()->route('huesped.dashboard')
            ->with('success', $mensaje);
    }


    /**
     * Show the form for editing an existing reservation.
     */
    public function edit(HttpRequest $request, Reservacion $reservacion): View|RedirectResponse
    {
        if ($reservacion->user_id !== $request->user()->id) {
            abort(403);
        }

        if (!$reservacion->puedeModificarse()) {
            return redirect()->route('huesped.dashboard')
                ->with('error', 'Esta reservación ya no se puede editar.');
        }

        $reservacion->load(['habitacion.imagenes', 'habitacion.tipoHabitacion']);

        $habitaciones = Habitacion::with(['tipoHabitacion', 'imagenPrincipal'])
            ->orderBy('numero')
            ->get();

        return view('public.huesped.reservaciones.edit', [
            'reservacion' => $reservacion,
            'habitaciones' => $habitaciones,
        ]);
    }


    /**
     * Update the specified reservation with new details.
     */
    public function update(HttpRequest $request, Reservacion $reservacion): RedirectResponse
    {
        if ($reservacion->user_id !== $request->user()->id) {
            abort(403);
        }

        if (!$reservacion->puedeModificarse()) {
            return redirect()->route('huesped.dashboard')
                ->with('error', 'Esta reservación ya no puede modificarse.');
        }

        $validated = $request->validate([
            'habitacion_id'     => ['required', 'exists:habitaciones,id'],
            'fecha_entrada'     => ['required', 'date', 'after_or_equal:today'],
            'fecha_salida'      => ['required', 'date', 'after:fecha_entrada'],
            'numero_huespedes'  => ['required', 'integer', 'min:1'],
            'notas'             => ['nullable', 'string', 'max:500'],
        ]);

        $habitacion = Habitacion::with('tipoHabitacion')->findOrFail($validated['habitacion_id']);

        if ($habitacion->estado === 'mantenimiento') {
            return back()->withInput()
                ->withErrors(['habitacion_id' => 'La habitación está en mantenimiento.']);
        }

        if ($validated['numero_huespedes'] > $habitacion->capacidad) {
            return back()->withInput()
                ->withErrors(['numero_huespedes' => "Máximo {$habitacion->capacidad} personas para esta habitación."]); 
        }

        $fechaEntrada = Carbon::parse($validated['fecha_entrada'])->startOfDay();
        $fechaSalida  = Carbon::parse($validated['fecha_salida'])->startOfDay();

        if (!$habitacion->estaDisponible($fechaEntrada, $fechaSalida, $reservacion->id)) {
            return back()->withInput()
                ->withErrors(['habitacion_id' => 'Lo sentimos, la habitación ya está reservada para esas fechas.']);
        }

        $noches = $fechaEntrada->diffInDays($fechaSalida);
        if ($noches < 1) {
            return back()->withInput()
                ->withErrors(['fecha_salida' => 'Debes reservar al menos una noche.']);
        }

        $precioNoche = (float) $habitacion->precio_actual;
        $nuevoTotal = round($noches * $precioNoche, 2);
        $pagado = (float) $reservacion->pagos()->where('estado', 'completado')->sum('monto');
        $nuevoSaldo = max(0, round($nuevoTotal - $pagado, 2));

        $estadoOriginal = $reservacion->estado;
        $nuevoEstado = $estadoOriginal;

        if ($nuevoSaldo > 0) {
            $nuevoEstado = 'pendiente';
        } else {
            if (in_array($estadoOriginal, ['activa', 'completada'], true)) {
                $nuevoEstado = $estadoOriginal;
            } else {
                $nuevoEstado = 'confirmada';
            }
        }

        $reservacion->update([
            'habitacion_id'    => $habitacion->id,
            'fecha_entrada'    => $fechaEntrada,
            'fecha_salida'     => $fechaSalida,
            'numero_huespedes' => $validated['numero_huespedes'],
            'precio_total'     => $nuevoTotal,
            'estado'           => $nuevoEstado,
            'notas'            => $validated['notas'] ?? null,
        ]);

        $mensaje = 'Reservación actualizada correctamente.';
        if ($nuevoSaldo > 0) {
            $mensaje .= ' Existe un cargo adicional de $' . number_format($nuevoSaldo, 2) . ' MXN por confirmar.';
        }

        return redirect()->route('huesped.dashboard')->with('success', $mensaje);
    }


    /**
     * Cancel a reservation that belongs to the authenticated guest.
     */
    public function destroy(HttpRequest $request, Reservacion $reservacion): RedirectResponse
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

