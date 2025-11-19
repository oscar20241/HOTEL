<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservacion;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use App\Models\User;
use Carbon\Carbon;

class RecepcionistaController extends Controller
{
    public function dashboard()
    {
        $hoy = Carbon::today();

        // ✅ Tu tabla tiene fecha_entrada / fecha_salida, NO fecha_checkin
        $reservasPendientes = Reservacion::whereDate('fecha_entrada', $hoy)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->count();

        // Habitaciones disponibles
        $habitacionesDisponibles = Habitacion::where('estado', 'disponible')->count();

        // Tipos de habitación para el select del formulario
        $tiposHabitacion = TipoHabitacion::orderBy('nombre')->get();

        // Huespedes = usuarios que NO tienen registro en empleados
        $huespedes = User::whereDoesntHave('empleado')
            ->orderBy('name')
            ->get();

        // ⚠️ Asegúrate que la vista se llame resources/views/recepcionista.blade.php
        return view('recepcionista', compact(
            'reservasPendientes',
            'habitacionesDisponibles',
            'tiposHabitacion',
            'huespedes'
        ));
    }

    // ========================
    //  NUEVA RESERVACIÓN
    // ========================
    public function storeReserva(Request $request)
    {
        $data = $request->validate([
            'user_id'            => 'required|exists:users,id',
            'tipo_habitacion_id' => 'required|exists:tipo_habitaciones,id',
            'fecha_entrada'      => 'required|date|after_or_equal:today',
            'fecha_salida'       => 'required|date|after:fecha_entrada',
            'adultos'            => 'required|integer|min:1',
            'ninos'              => 'nullable|integer|min:0',
            'telefono'           => 'nullable|string|max:30',
            'notas'              => 'nullable|string|max:500',
        ], [
            'user_id.required' => 'Debes seleccionar un huésped.',
            'user_id.exists'   => 'El huésped seleccionado no existe.',
        ]);

        $entrada = Carbon::parse($data['fecha_entrada']);
        $salida  = Carbon::parse($data['fecha_salida']);
        $noches  = $entrada->diffInDays($salida);

        if ($noches <= 0) {
            return back()
                ->withErrors(['fecha_salida' => 'La fecha de salida debe ser posterior a la de entrada.'])
                ->withInput();
        }

        $tipo = TipoHabitacion::findOrFail($data['tipo_habitacion_id']);

        // Puedes usar el accesor `precio_actual` que ya tienes en el modelo
        $precioNoche = $tipo->precio_actual;
        $precioTotal = $noches * $precioNoche;

        // Buscar una habitación libre de ese tipo
        $habitacionLibre = Habitacion::where('tipo_habitacion_id', $tipo->id)
            ->where('estado', 'disponible')
            ->first();

        if (!$habitacionLibre) {
            return back()
                ->withErrors(['tipo_habitacion_id' => 'No hay habitaciones disponibles de este tipo.'])
                ->withInput();
        }

        $notas = $data['notas'] ?? '';
        if (!empty($data['telefono'])) {
            $notas = trim($notas . "\nTeléfono de contacto: " . $data['telefono']);
        }

        $numeroHuespedes = ($data['adultos'] ?? 0) + ($data['ninos'] ?? 0);

        Reservacion::create([
            'user_id'          => $data['user_id'],
            'habitacion_id'    => $habitacionLibre->id,
            'fecha_entrada'    => $data['fecha_entrada'],
            'fecha_salida'     => $data['fecha_salida'],
            'precio_total'     => $precioTotal,
            'estado'           => 'pendiente',
            'notas'            => $notas ?: null,
            'metodo_pago'      => 'pendiente',
            'numero_huespedes' => $numeroHuespedes,
        ]);

        return redirect()
            ->route('recepcion.dashboard')
            ->with('success', 'Reservación creada correctamente.');
    }

    // ========================
    //  VISTAS EXTRA (si las sigues usando)
    // ========================
    public function reservaciones()
    {
        return view('recepcionista.reservaciones');
    }

    public function checkin()
    {
        return view('recepcionista.checkin');
    }

    // ========================
    //  CANCELAR RESERVA (AJAX)
    // ========================
    public function cancelarReservacion(Request $request)
    {
        try {
            $reservacion = Reservacion::findOrFail($request->reservacion_id);
            $reservacion->update(['estado' => 'cancelada']);
            
            return response()->json([
                'success' => true,
                'message' => 'Reservación cancelada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar reservación'
            ], 500);
        }
    }
    
    public function buscarHistorial(Request $request)
    {
        // Aquí luego lo adaptamos a tu estructura final (User + Reservacion)
    }
    
    public function checkout(Request $request)
    {
        try {
            // Aquí iría la lógica real de checkout
            return response()->json([
                'success' => true,
                'message' => 'Check-out realizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en check-out'
            ], 500);
        }
    }

    public function reservasDelDia()
    {
        $hoy = Carbon::today();

        $reservas = Reservacion::with(['user', 'habitacion.tipoHabitacion'])
            ->whereDate('fecha_entrada', '<=', $hoy)
            ->whereDate('fecha_salida', '>=', $hoy)
            ->orderBy('fecha_entrada')
            ->get()
            ->map(function ($reserva) {
                $estado = strtolower($reserva->estado ?? 'pendiente');
                return [
                    'id'        => $reserva->id,
                    'codigo'    => $reserva->codigo_reserva,
                    'huesped'   => optional($reserva->user)->name ?? 'Huésped',
                    'habitacion'=> optional($reserva->habitacion)->numero ?? 'N/A',
                    'tipo'      => optional(optional($reserva->habitacion)->tipoHabitacion)->nombre,
                    'checkin'   => optional($reserva->fecha_entrada)->format('Y-m-d'),
                    'checkout'  => optional($reserva->fecha_salida)->format('Y-m-d'),
                    'estado'    => ucfirst($estado),
                    'puede_checkin'  => in_array($estado, ['pendiente', 'confirmada']),
                    'puede_checkout' => $estado === 'activa',
                ];
            });

        return response()->json($reservas);
    }

    public function filtrarOcupacion(Request $request)
    {
        $data = $request->validate([
            'inicio' => 'required|date',
            'fin'    => 'required|date|after_or_equal:inicio',
        ]);

        $inicio = Carbon::parse($data['inicio']);
        $fin    = Carbon::parse($data['fin']);

        $reservas = Reservacion::with(['user', 'habitacion'])
            ->where(function ($query) use ($inicio, $fin) {
                $query->where('fecha_entrada', '<=', $fin)
                      ->where('fecha_salida', '>=', $inicio);
            })
            ->orderBy('fecha_entrada')
            ->get();

        $resultados = $reservas->map(function ($reserva) {
            $estadoOcupacion = in_array($reserva->estado, ['activa', 'confirmada'])
                ? 'Ocupada'
                : 'Reservada';

            return [
                'habitacion' => optional($reserva->habitacion)->numero ?? 'N/A',
                'estado'     => $estadoOcupacion,
                'huesped'    => optional($reserva->user)->name ?? 'Huésped',
                'entrada'    => optional($reserva->fecha_entrada)->format('Y-m-d'),
                'salida'     => optional($reserva->fecha_salida)->format('Y-m-d'),
            ];
        });

        return response()->json($resultados);
    }

    public function hacerCheckin(Request $request)
    {
        $data = $request->validate([
            'codigo_reserva' => 'required|string',
        ]);

        $reservacion = Reservacion::with('habitacion')
            ->where('codigo_reserva', $data['codigo_reserva'])
            ->first();

        if (!$reservacion) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una reservación con ese código.',
            ], 404);
        }

        if (!in_array($reservacion->estado, ['pendiente', 'confirmada'])) {
            return response()->json([
                'success' => false,
                'message' => 'La reservación no está disponible para check-in.',
            ], 422);
        }

        $reservacion->update([
            'estado'        => 'activa',
            'fecha_checkin' => Carbon::now(),
        ]);

        if ($reservacion->habitacion) {
            $reservacion->habitacion->update(['estado' => 'ocupada']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in registrado correctamente.',
        ]);
    }

    public function hacerCheckout(Request $request)
    {
        $data = $request->validate([
            'codigo_reserva' => 'required|string',
        ]);

        $reservacion = Reservacion::with('habitacion')
            ->where('codigo_reserva', $data['codigo_reserva'])
            ->first();

        if (!$reservacion) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una reservación con ese código.',
            ], 404);
        }

        if ($reservacion->estado !== 'activa') {
            return response()->json([
                'success' => false,
                'message' => 'Solo las reservaciones activas pueden hacer check-out.',
            ], 422);
        }

        $reservacion->update([
            'estado'          => 'finalizada',
            'fecha_checkout'  => Carbon::now(),
        ]);

        if ($reservacion->habitacion) {
            $reservacion->habitacion->update(['estado' => 'disponible']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-out registrado correctamente.',
        ]);
    }
}
