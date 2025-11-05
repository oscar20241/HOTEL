<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User; // ← AÑADIR ESTA IMPORTACIÓN

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // PRIMERO: Verificar si el usuario existe y es empleado inactivo
        $user = User::where('email', $this->email)->first();
        
        if ($user && $user->empleado) {
            // Verificar el estado del empleado
            switch ($user->empleado->estado) {
                case 'inactivo':
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => '❌ Tu cuenta está desactivada. Contacta al administrador.',
                    ]);
                    
                case 'vacaciones':
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => '🏖️ Tu cuenta está en modo vacaciones. Vuelve a activarla cuando regreses.',
                    ]);
                    
                case 'licencia':
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => '📄 Tu cuenta está en licencia. Contacta al administrador para reactivarla.',
                    ]);
            }
        }

        // SEGUNDO: Intentar autenticación normal
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}