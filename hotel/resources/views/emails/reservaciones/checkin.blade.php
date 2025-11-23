<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Check-in registrado</title>
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
        <h1>¡Tu check-in está listo!</h1>
        <p>Hola {{ $reservacion->user->name }},</p>
        <p>
            Hemos registrado tu check-in para la reservación <strong>{{ $reservacion->codigo_reserva }}</strong>.
            Estos son los datos principales de tu estancia.
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
            <div class="section-title">Estado</div>
            <table>
                <tr>
                    <td class="label">Check-in</td>
                    <td class="value">{{ optional($reservacion->fecha_checkin)->format('d/m/Y H:i') ?? 'Registrado' }}</td>
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

        <p>Si necesitas ayuda adicional durante tu estancia, contáctanos cuando lo requieras.</p>

        <div class="footer">
            PASA EL EXTRA INN<br>
            Este mensaje se generó automáticamente, por favor no respondas si no es necesario.
        </div>
    </div>
</body>
</html>
