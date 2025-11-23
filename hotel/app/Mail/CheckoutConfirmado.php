<?php

namespace App\Mail;

use App\Models\Reservacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutConfirmado extends Mailable
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
            ->subject("Check-out completado para la reservación {$codigo}")
            ->view('emails.reservaciones.checkout');
    }
}
