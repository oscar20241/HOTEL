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
            'numero_huespedes'   => 'required|integer|min:1',
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

        Reservacion::create([
            'user_id'          => $data['user_id'],
            'habitacion_id'    => $habitacionLibre->id,
            'fecha_entrada'    => $data['fecha_entrada'],
            'fecha_salida'     => $data['fecha_salida'],
            'precio_total'     => $precioTotal,
            'estado'           => 'pendiente',
            'notas'            => $data['notas'] ?? null,
            'metodo_pago'      => 'pendiente',
            'numero_huespedes' => $data['numero_huespedes'],
        ]);

        // Podrías marcar la habitación como "ocupada" o "reservada" si quieres:
        // $habitacionLibre->update(['estado' => 'ocupada']);

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
}
