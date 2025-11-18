<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservacion;
use App\Models\Cliente;

class RecepcionistaController extends Controller
{
    public function dashboard()
    {
        return view('recepcionista');
    }
    
    public function reservaciones()
    {
        return view('recepcionista.reservaciones');
    }
    
    public function checkin()
    {
        return view('recepcionista.checkin');
    }
    
    // 🆕 MÉTODOS NUEVOS
    public function cancelarReservacion(Request $request)
    {
        // Lógica para cancelar reservación
        try {
            $reservacion = Reservacion::findOrFail($request->reservacion_id);
            $reservacion->update(['estado' => 'cancelada']);
            
            return response()->json(['success' => true, 'message' => 'Reservación cancelada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al cancelar reservación'], 500);
        }
    }
    
    public function buscarHistorial(Request $request)
    {
        $busqueda = $request->get('busqueda');
        
        $clientes = Cliente::with(['reservaciones' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->where('nombre', 'LIKE', "%{$busqueda}%")
        ->orWhere('email', 'LIKE', "%{$busqueda}%")
        ->orWhere('documento', 'LIKE', "%{$busqueda}%")
        ->get();
        
        return response()->json($clientes);
    }
    
    public function checkout(Request $request)
    {
        // Lógica para checkout
        try {
            // Tu lógica de checkout aquí
            return response()->json(['success' => true, 'message' => 'Check-out realizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error en check-out'], 500);
        }
    }
}