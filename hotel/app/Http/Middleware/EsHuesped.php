<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EsHuesped
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && (auth()->user()->esHuesped() || auth()->user()->esRecepcionista() || auth()->user()->esAdministrador())) {
            return $next($request);
        }
        
        return redirect('/dashboard')->with('error', 'No tienes permisos para acceder');
    }
}