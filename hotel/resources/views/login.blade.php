<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar sesión · Hotel Aurora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-200">
    <div class="relative min-h-screen">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(79,70,229,0.45),transparent_60%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,_rgba(15,23,42,0.75),transparent_55%)]"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 py-16 flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1 text-center lg:text-left space-y-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-white/80 hover:text-white transition">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.5c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v4.5h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                        </svg>
                    </span>
                    Regresar al inicio
                </a>
                <h1 class="text-4xl font-['Playfair_Display'] text-white">Tu estadía comienza aquí</h1>
                <p class="text-base text-white/70 max-w-md">Accede a tu cuenta para gestionar reservaciones, actualizar tus datos y descubrir experiencias diseñadas para ti en Hotel Aurora.</p>
            </div>

            <div class="flex-1 max-w-lg w-full">
                <div class="bg-white/10 backdrop-blur border border-white/10 rounded-3xl shadow-2xl shadow-black/40 p-10">
                    <div class="flex items-center justify-center gap-3 mb-8">
                        <img src="{{ asset('/img/logo.png') }}" alt="Hotel Aurora" class="h-14">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-white/60">Hotel Aurora</p>
                            <p class="text-lg font-semibold text-white">Área de huéspedes</p>
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="mb-6 p-3 rounded-xl bg-emerald-500/10 border border-emerald-400/30 text-emerald-100 text-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-3 rounded-xl bg-rose-500/10 border border-rose-400/30 text-rose-100 text-sm">
                            <p class="font-semibold mb-2">No pudimos iniciar sesión:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-semibold text-white/70">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="correo@ejemplo.com" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-semibold text-white/70">Contraseña</label>
                            <input type="password" id="password" name="password" required placeholder="••••••••" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-indigo-500 text-white font-semibold hover:bg-indigo-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 3.75l-.666 1.995M5.25 9h13.5m-.906 11.25l-.666-1.995M7.5 21l-.666-1.995m6.816-15.255l.666 1.995m2.95 9.51l.666 1.995M12 6.75v9" />
                            </svg>
                            Iniciar sesión
                        </button>
                    </form>

                    <div class="mt-8 text-sm text-white/70 space-y-3">
                        <p class="text-white">¿Aún no tienes cuenta?</p>
                        <a href="{{ route('registro') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full border border-white/20 text-white font-semibold hover:bg-white/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Crear una cuenta
                        </a>
                        <div class="pt-4 border-t border-white/10 text-xs text-white/60">
                            <p class="uppercase tracking-[0.4em] text-white/40 mb-2">Accesos de prueba</p>
                            <p><span class="font-semibold text-white/80">Admin:</span> admin@hotel.com / password</p>
                            <p><span class="font-semibold text-white/80">Recepción:</span> recepcion@hotel.com / password</p>
                            <p><span class="font-semibold text-white/80">Huésped:</span> huesped@ejemplo.com / password</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
