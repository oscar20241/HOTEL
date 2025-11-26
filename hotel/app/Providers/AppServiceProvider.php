<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\App;
use App\Mail\BrevoTransport;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(MailManager $mailManager)
    {
        // Registrar driver Brevo
        $mailManager->extend('brevo', function ($config) {
            $apiKey = config('services.brevo.api_key');
            return new BrevoTransport($apiKey);
        });

        // Forzar español para fechas y mensajes del sistema
        Carbon::setLocale('es');
        CarbonInterval::setLocale('es');
        App::setLocale('es'); // <- Esto hará que todos los mensajes de validación y auth estén en español
    }
}
