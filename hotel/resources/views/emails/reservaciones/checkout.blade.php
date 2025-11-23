@php
    $reservacion->loadMissing(['habitacion.tipoHabitacion', 'user']);
    $habitacion = $reservacion->habitacion;
    $tipo = optional($habitacion)->tipoHabitacion;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-out completado</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; color: #0f172a; padding: 24px;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background: linear-gradient(90deg, #312e81, #4338ca); padding: 24px; color: #fff;">
                            <p style="margin: 0; text-transform: uppercase; letter-spacing: 3px; font-size: 12px; opacity: 0.8;">Reservación {{ $reservacion->codigo_reserva }}</p>
                            <h1 style="margin: 8px 0 0; font-size: 24px;">Check-out completado</h1>
                            <p style="margin: 6px 0 0; font-size: 14px; opacity: 0.9;">Gracias por tu visita, aquí tienes el resumen final.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px;">
                            <p style="font-size: 15px; line-height: 1.6; margin-top: 0;">Hola {{ $reservacion->user->name ?? 'Huésped' }},</p>
                            <p style="font-size: 15px; line-height: 1.6;">Te compartimos los detalles de tu salida:</p>
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-top: 16px;">
                                <tr>
                                    <td style="padding: 12px; background: #f1f5f9; border-radius: 10px;">
                                        <strong style="display: block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: #475569;">Tipo de habitación</strong>
                                        <span style="font-size: 15px; color: #0f172a;">{{ $tipo?->nombre ?? 'Habitación asignada' }}@if($habitacion?->numero) &mdash; Hab. {{ $habitacion->numero }}@endif</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px;">
                                        <strong style="display: block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: #475569;">Fechas</strong>
                                        <span style="font-size: 15px; color: #0f172a;">{{ $reservacion->fecha_entrada->format('d M Y') }} - {{ $reservacion->fecha_salida->format('d M Y') }} ({{ $reservacion->noches }} noche(s))</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; background: #f1f5f9; border-radius: 10px;">
                                        <strong style="display: block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: #475569;">Huéspedes</strong>
                                        <span style="font-size: 15px; color: #0f172a;">{{ $reservacion->numero_huespedes }} persona(s)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px;">
                                        <strong style="display: block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: #475569;">Total pagado</strong>
                                        <span style="font-size: 15px; color: #0f172a;">${{ number_format($reservacion->total_pagado, 2) }} MXN</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; background: #f1f5f9; border-radius: 10px;">
                                        <strong style="display: block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: #475569;">Saldo pendiente</strong>
                                        <span style="font-size: 15px; color: #0f172a;">${{ number_format($reservacion->saldo_pendiente, 2) }} MXN</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px;">
                                        <strong style="display: block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: #475569;">Check-out</strong>
                                        <span style="font-size: 15px; color: #0f172a;">{{ optional($reservacion->fecha_checkout)->format('d M Y H:i') ?? 'Registrado' }}</span>
                                    </td>
                                </tr>
                                @if(!empty($reservacion->notas))
                                    <tr>
                                        <td style="padding: 12px; background: #f1f5f9; border-radius: 10px;">
                                            <strong style="display: block; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; color: #475569;">Notas</strong>
                                            <span style="font-size: 15px; color: #0f172a;">{{ $reservacion->notas }}</span>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-top: 20px;">Agradecemos tu confianza. Si tienes comentarios sobre tu estancia, respóndenos y los atenderemos.</p>
                            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-top: 20px;">Esperamos verte pronto de nuevo.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 24px; background: #0f172a; color: #e2e8f0; text-align: center; font-size: 12px;">
                            © {{ date('Y') }} Hotel PASA EL EXTRA Inn. Todos los derechos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
