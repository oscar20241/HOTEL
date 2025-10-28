<?php

namespace App\Http\Middleware;

use App\Models\Empleado;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmpleadoStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $empleado = Empleado::where('user_id', $user->id)->first();

            // Si es empleado (de cualquier rol), verificar su estado
            if ($empleado) {
                switch ($empleado->estado) {
                    case 'inactivo':
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login')
                            ->with('error', '❌ Tu cuenta está desactivada. Contacta al administrador.');

                    case 'vacaciones':
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login')
                            ->with('error', '🏖️ Tu cuenta está en modo vacaciones. Vuelve a activarla cuando regreses.');

                    case 'licencia':
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login')
                            ->with('error', '📄 Tu cuenta está en licencia. Contacta al administrador para reactivarla.');
                }
            }
        }

        return $next($request);
    }
}