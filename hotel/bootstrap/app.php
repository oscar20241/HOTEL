<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // REGISTRAR MIDDLEWERS AQUÍ DENTRO:
        $middleware->alias([
            'es.admin' => App\Http\Middleware\EsAdministrador::class,
            'es.recepcionista' => App\Http\Middleware\EsRecepcionista::class,
            'es.huesped' => App\Http\Middleware\EsHuesped::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();