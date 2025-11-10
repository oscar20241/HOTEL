<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta · Hotel Aurora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="relative min-h-screen">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(79,70,229,0.35),transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom,_rgba(30,64,175,0.25),transparent_55%)]"></div>
        </div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 py-16 grid lg:grid-cols-[1fr_1.1fr] gap-12 items-center">
            <div class="text-center lg:text-left space-y-6">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-3 text-white/80 hover:text-white transition">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </span>
                    ¿Ya tienes cuenta?
                </a>
                <h1 class="text-4xl font-['Playfair_Display'] text-white">Regístrate y vive la experiencia Aurora</h1>
                <p class="text-base text-white/70 max-w-md">Completa tus datos para reservar de manera más ágil, recibir recomendaciones personalizadas y acceder a beneficios exclusivos para huéspedes frecuentes.</p>
                <div class="flex flex-wrap gap-4 text-sm text-white/70">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c.463-.293.837-.74 1.012-1.29C12.684 1.64 13.405 1 14.25 1h.498a2.25 2.25 0 012.25 2.25v.936c0 .47.15.926.43 1.301l.06.08a2.25 2.25 0 010 2.77l-.06.08a2.25 2.25 0 00-.43 1.3v.937a2.25 2.25 0 01-2.25 2.25H14.25c-.844 0-1.565-.64-1.888-1.546a2.25 2.25 0 00-1.012-1.29l-.523-.331a2.25 2.25 0 010-3.78l.523-.331z" />
                            </svg>
                        </span>
                        Reservaciones más rápidas
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25c-2.071 0-4.026.56-5.68 1.53C4.533 16.932 4 18.026 4 19.185V20.25h16v-1.065c0-1.159-.533-2.253-2.32-3.405-1.653-.97-3.608-1.53-5.68-1.53z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v-3M15 4.5v-3" />
                            </svg>
                        </span>
                        Experiencias personalizadas
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </span>
                        Beneficios exclusivos
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur border border-white/10 rounded-3xl shadow-2xl shadow-black/40 p-10">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('/img/logo.png') }}" alt="Hotel Aurora" class="h-12">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-white/60">Hotel Aurora</p>
                        <p class="text-lg font-semibold text-white">Registro de huéspedes</p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-3 rounded-xl bg-rose-500/10 border border-rose-400/30 text-rose-100 text-sm">
                        <p class="font-semibold mb-2">Por favor revisa los siguientes campos:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 p-3 rounded-xl bg-emerald-500/10 border border-emerald-400/30 text-emerald-100 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('registro.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-semibold text-white/70">Nombre completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Ej. María González" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-white/70">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="correo@ejemplo.com" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-white/70">Contraseña</label>
                            <input type="password" id="password" name="password" required placeholder="••••••••" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-white/70">Confirmar contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repite tu contraseña" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="telefono" class="block text-sm font-semibold text-white/70">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}" required placeholder="55 1234 5678" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-semibold text-white/70">Fecha de nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                    </div>
                    <div>
                        <label for="direccion" class="block text-sm font-semibold text-white/70">Dirección</label>
                        <input type="text" id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Calle, número, ciudad" class="mt-2 w-full rounded-xl border border-white/20 bg-white/10 text-white placeholder-white/40 focus:border-indigo-400 focus:ring-indigo-400">
                    </div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-indigo-500 text-white font-semibold hover:bg-indigo-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Crear cuenta
                    </button>
                </form>

                <p class="mt-6 text-xs text-white/60 text-center">Al registrarte aceptas nuestros términos de servicio y política de privacidad.</p>
            </div>
        </div>
    </div>
</body>
</html>
