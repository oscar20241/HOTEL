<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hotel Aurora') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;playfair-display:600&display=swap" rel="stylesheet" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-700">
    <header class="bg-white/95 backdrop-blur shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-semibold text-indigo-600">
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5m-9 3.75h10.5M9 21h6a2.25 2.25 0 002.25-2.25V5.25A2.25 2.25 0 0015 3H9a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 009 21z" />
                        </svg>
                    </span>
                    <span class="font-['Playfair_Display'] tracking-wide">Hotel Aurora</span>
                </a>
                <div class="flex items-center gap-4">
                    @auth
                        <span class="hidden sm:inline text-sm text-slate-500">Hola, {{ Auth::user()->name }}</span>
                        @if (Auth::user()->esHuesped())
                            <a href="{{ route('huesped.dashboard') }}#mis-reservas" class="hidden sm:inline-flex items-center gap-2 text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m6.75-2.25v12A2.25 2.25 0 0119.5 21H4.5A2.25 2.25 0 012.25 18V6A2.25 2.25 0 014.5 3.75h15A2.25 2.25 0 0121.75 6z" />
                                </svg>
                                Mis reservaciones
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75l7.5-3 7.5 3m-15 0l7.5 3 7.5-3m-15 0v10.5l7.5 3m7.5-13.5v10.5l-7.5 3" />
                            </svg>
                            Ir a tu panel
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H3" />
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">
                            <span class="flex items-center justify-center h-9 w-9 rounded-full border border-indigo-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                            Crear cuenta
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid gap-8 md:grid-cols-3">
            <div>
                <h3 class="text-lg font-semibold mb-3">Hotel Aurora</h3>
                <p class="text-sm text-slate-400">Vive una experiencia inolvidable en el corazón de la ciudad. Habitaciones elegantes, servicio de primera y amenidades pensadas para tu descanso.</p>
            </div>
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-2">Contacto</h4>
                <ul class="space-y-1 text-sm text-slate-300">
                    <li>Teléfono: (55) 1234 5678</li>
                    <li>Email: reservas@hotel-aurora.com</li>
                    <li>Dirección: Av. Reforma 123, Ciudad de México</li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-2">Síguenos</h4>
                <div class="flex items-center gap-3">
                    <a href="#" class="h-9 w-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3h9A4.5 4.5 0 0121 7.5v9A4.5 4.5 0 0116.5 21h-9A4.5 4.5 0 013 16.5v-9A4.5 4.5 0 017.5 3zm9 4.5h.008v.008H16.5V7.5zM12 9.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" />
                        </svg>
                    </a>
                    <a href="#" class="h-9 w-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                            <path d="M22 12.073C22 5.943 17.303 1 12 1S2 5.943 2 12.073c0 5.026 3.657 9.204 8.438 9.876v-6.987H7.898V12.07h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.774-1.63 1.562v1.846h2.773l-.443 2.892h-2.33v6.987C18.343 21.277 22 17.099 22 12.073z" />
                        </svg>
                    </a>
                    <a href="#" class="h-9 w-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                            <path d="M20.135 7.55c.013.176.013.353.013.53 0 5.386-4.099 11.6-11.6 11.6-2.307 0-4.456-.676-6.26-1.84.33.04.648.053.99.053a8.2 8.2 0 005.086-1.75 4.1 4.1 0 01-3.83-2.846c.254.04.508.066.775.066.368 0 .736-.053 1.078-.14a4.092 4.092 0 01-3.282-4.013v-.053a4.14 4.14 0 001.854.516 4.094 4.094 0 01-1.821-3.41c0-.763.204-1.45.56-2.055a11.62 11.62 0 008.431 4.28 4.62 4.62 0 01-.102-.94 4.093 4.093 0 017.084-2.8 8.087 8.087 0 002.598-.99 4.096 4.096 0 01-1.8 2.257 8.18 8.18 0 002.35-.63 8.817 8.817 0 01-2.048 2.12z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-slate-500">
            © {{ date('Y') }} Hotel Aurora. Todos los derechos reservados.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
