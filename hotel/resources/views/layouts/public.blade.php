<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hotel PASA EL EXTRA Inn') }}</title>

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
                    <span class="font-semibold tracking-wide">HOTEL PASA EL EXTRA INN</span>
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
                <h3 class="text-lg font-semibold mb-3">HOTEL PASA EL EXTRA INN</h3>
                <p class="text-sm text-slate-400">Vive una experiencia inolvidable en el corazón de la ciudad. Habitaciones elegantes, servicio de primera y amenidades pensadas para tu descanso.</p>
            </div>
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-2">Contacto</h4>
                <ul class="space-y-1 text-sm text-slate-300">
                    <li>Teléfono: (329) 322-4850</li>
                    <li>Correo electrónico: reservas@pasaelextrainn.com</li>
                   <li>Dirección: Av. Paseo de los cocoteros, Nuevo Vallarta, Nayarit, C.P. 63735, México</li>
                </ul>
            </div>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-slate-500">
            © {{ date('Y') }} Hotel PASA EL EXTRA Inn. Todos los derechos reservados.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
