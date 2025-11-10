<?php

namespace App\Http\Controllers;

use App\Models\Reservacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PagoController extends Controller
{
    // --- Helpers PayPal ---
    protected function paypalApiBase(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api.paypal.com'
            : 'https://api.sandbox.paypal.com';
    }

    protected function paypalAccessToken(): string
    {
        $base = $this->paypalApiBase();

        $res = Http::asForm()
            ->withBasicAuth(config('services.paypal.client_id'), config('services.paypal.secret'))
            ->post("$base/v1/oauth2/token", ['grant_type' => 'client_credentials']);

        abort_if(!$res->ok(), 502, 'No se pudo obtener token de PayPal');
        return $res->json('access_token');
    }

    /**
     * POST /reservaciones/{reservacion}/pago/paypal
     * Verifica/captura el pago en PayPal Sandbox y registra el pago en tu BD.
     */
    public function storePaypal(Request $request, Reservacion $reservacion): JsonResponse
    {
        // Seguridad: la reservación debe pertenecer al usuario autenticado
        $user = $request->user();
        if (!$user || $reservacion->user_id !== $user->id) {
            abort(403);
        }

        // Calcular saldo pendiente antes de continuar
        $saldoPendienteFloat = (float) $reservacion->saldo_pendiente;
        $saldoPendiente = number_format($saldoPendienteFloat, 2, '.', '');

        if ($saldoPendienteFloat <= 0) {
            return response()->json([
                'message' => 'No hay saldo pendiente por cobrar en esta reservación.',
            ], 422);
        }

        // Solo permitir pago si está pendiente
        if ($reservacion->estado !== 'pendiente') {
            return response()->json([
                'message' => 'La reservación ya fue procesada o cancelada.',
            ], 422);
        }

        $validated = $request->validate([
            'paypal_order_id' => ['required', 'string', 'max:191'],
        ]);

        $orderId = $validated['paypal_order_id'];

        // 1) Obtener token y consultar/capturar la orden
        $base  = $this->paypalApiBase();
        $token = $this->paypalAccessToken();

        // Si ya capturaste en el cliente (onApprove -> actions.order.capture()):
        $order = Http::withToken($token)->get("$base/v2/checkout/orders/{$orderId}")->json();

        // (Alternativa) Capturar en servidor si no capturas en el cliente:
        // $order = Http::withToken($token)->post("$base/v2/checkout/orders/{$orderId}/capture")->json();

        if (($order['status'] ?? null) !== 'COMPLETED') {
            return response()->json([
                'message' => 'La orden de PayPal no está COMPLETED.',
                'detalles' => $order,
            ], 422);
        }

        // 2) Validar monto y moneda
        $pu        = $order['purchase_units'][0] ?? [];
        $amountVal = $pu['amount']['value'] ?? null;
        $currency  = $pu['amount']['currency_code'] ?? null;

        if ($amountVal !== $saldoPendiente || $currency !== 'MXN') {
            return response()->json([
                'message' => 'El monto o la moneda no coinciden con la reservación.',
                'paypal_amount' => compact('amountVal', 'currency'),
                'esperado' => ['value' => $saldoPendiente, 'currency' => 'MXN'],
            ], 422);
        }

        // 3) Registrar pago y confirmar reservación (transacción)
        $pagoId = null;
        DB::transaction(function () use ($reservacion, $orderId, $saldoPendienteFloat, &$pagoId) {
            $pago = $reservacion->pagos()->create([
                'monto'        => $saldoPendienteFloat,
                'metodo_pago'  => 'paypal',
                'estado'       => 'completado',
                'referencia'   => $orderId,
                'fecha_pago'   => now(),
            ]);
            $pagoId = $pago->id;

            $saldoRestante = max(0, (float) $reservacion->precio_total - (float) $reservacion->pagos()
                ->where('estado', 'completado')
                ->sum('monto'));

            $reservacion->update([
                'estado' => $saldoRestante <= 0 ? 'confirmada' : 'pendiente',
            ]);
        });

        return response()->json([
            'message' => 'Pago registrado correctamente.',
            'pago_id' => $pagoId,
        ]);
    }
}
