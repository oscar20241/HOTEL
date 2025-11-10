<?php

namespace App\Http\Controllers;

use App\Models\Reservacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Store a simulated PayPal payment for the authenticated guest.
     */
    public function storePaypal(Request $request, Reservacion $reservacion): JsonResponse
    {
        $user = $request->user();

        if ($reservacion->user_id !== $user->id) {
            abort(403);
        }

        if ($reservacion->estado !== 'pendiente') {
            return response()->json([
                'message' => 'La reservación ya fue procesada o cancelada.',
            ], 422);
        }

        $validated = $request->validate([
            'paypal_order_id' => ['required', 'string', 'max:191'],
        ]);

        $pago = $reservacion->pagos()->create([
            'monto' => $reservacion->precio_total,
            'metodo_pago' => 'paypal',
            'estado' => 'completado',
            'referencia' => $validated['paypal_order_id'],
            'fecha_pago' => now(),
        ]);

        $reservacion->update([
            'estado' => 'confirmada',
        ]);

        return response()->json([
            'message' => 'Pago registrado correctamente.',
            'pago_id' => $pago->id,
        ]);
    }
}
