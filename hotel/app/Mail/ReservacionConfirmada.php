<?php

namespace App\Mail;

use App\Models\Reservacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservacionConfirmada extends Mailable
{
    use Queueable, SerializesModels;

    public Reservacion $reservacion;

    public function __construct(Reservacion $reservacion)
    {
        $this->reservacion = $reservacion;
    }

    public function build(): self
    {
        $codigo = $this->reservacion->codigo_reserva;

        return $this
            ->subject("Confirmación de reservación {$codigo}")
            ->view('emails.reservaciones.confirmada');
    }
}
