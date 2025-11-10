<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de reservación</title>
    <style>
        body { font-family: Arial, sans-serif; color: #0f172a; }
        h1 { color: #0f172a; }
        .container { max-width: 600px; margin: 0 auto; padding: 24px; background-color: #f8fafc; border-radius: 12px; }
        .section { margin-bottom: 18px; }
        .section-title { font-weight: 600; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px 0; vertical-align: top; }
        .label { color: #475569; width: 45%; }
        .value { color: #0f172a; }
        .footer { margin-top: 32px; font-size: 0.875rem; color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Reservación confirmada!</h1>
        <p>Hola {{ $reservacion->user->name }},</p>
        <p>
            Hemos confirmado tu reservación <strong>{{ $reservacion->codigo_reserva }}</strong> después de recibir tu pago por PayPal.
            A continuación encontrarás los detalles principales de tu estancia.
        </p>

        <div class="section">
            <div class="section-title">Detalles de la reservación</div>
            <table>
                <tr>
                    <td class="label">Código de reservación</td>
                    <td class="value">{{ $reservacion->codigo_reserva }}</td>
                </tr>
                <tr>
                    <td class="label">Habitación</td>
                    <td class="value">
                        @php($habitacion = $reservacion->habitacion)
                        @php($tipo = optional($habitacion)->tipoHabitacion)
                        {{ $tipo?->nombre ?? 'Habitación asignada' }}
                        @if($habitacion?->numero)
                            &mdash; Hab. {{ $habitacion->numero }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Fechas</td>
                    <td class="value">
                        Entrada: {{ $reservacion->fecha_entrada->format('d/m/Y') }}<br>
                        Salida: {{ $reservacion->fecha_salida->format('d/m/Y') }}<br>
                        Noches: {{ $reservacion->noches }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Número de huéspedes</td>
                    <td class="value">{{ $reservacion->numero_huespedes }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Resumen de pago</div>
            <table>
                <tr>
                    <td class="label">Total pagado</td>
                    <td class="value">${{ number_format($reservacion->total_pagado, 2) }} MXN</td>
                </tr>
                <tr>
                    <td class="label">Saldo pendiente</td>
                    <td class="value">${{ number_format($reservacion->saldo_pendiente, 2) }} MXN</td>
                </tr>
            </table>
        </div>

        @if(!empty($reservacion->notas))
            <div class="section">
                <div class="section-title">Notas</div>
                <p class="value">{{ $reservacion->notas }}</p>
            </div>
        @endif

        <p>Si necesitas realizar algún cambio o tienes dudas, responde a este correo o contáctanos por los canales habituales.</p>

        <p>¡Te esperamos pronto!</p>

        <div class="footer">
            {{ config('app.name') }}<br>
            Este mensaje se generó automáticamente, por favor no respondas si no es necesario.
        </div>
    </div>
</body>
</html>
