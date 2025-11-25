<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use App\Mail\BrevoTransport;




class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(MailManager $mailManager)
{
    $mailManager->extend('brevo', function ($config) {
        $apiKey = config('services.brevo.api_key');

        // 👇 Esto es lo importante: regresar el TRANSPORT, no un Mailer completo
        return new BrevoTransport($apiKey);
    });
}



}
