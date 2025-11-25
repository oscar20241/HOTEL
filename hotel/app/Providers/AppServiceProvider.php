<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Carbon\Carbon;
use Carbon\CarbonInterval;

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

            // Este return debe estar SOLO
            return new BrevoTransport($apiKey);
        });

        // ESTE CÓDIGO NO VA ADENTRO DEL MAIL, VA AQUÍ
        Carbon::setLocale('es');
        CarbonInterval::setLocale('es');
    }
}
