@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-700 text-white">
        <div class="absolute inset-0 opacity-40" style="background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center;"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 flex flex-col lg:flex-row gap-10 items-center">
            <div class="lg:w-1/2 space-y-6">
                <p class="uppercase text-sm tracking-[0.3em] text-white/70">Bienvenido a Hotel PASA EL EXTRA Inn</p>
                <h1 class="text-4xl sm:text-5xl font-['Playfair_Display'] font-semibold leading-tight">Elige la categoría perfecta para tu estancia</h1>
                <p class="text-white/80 text-lg">Descubre nuestras suites, habitaciones dobles y opciones ejecutivas. Tú seleccionas el tipo de habitación y nosotros asignamos la mejor opción disponible para tus fechas.</p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="#categorias" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-900/30 hover:bg-slate-100 transition">
                        Explorar categorías
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                    <a href="#cta-reservar" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97a1.125 1.125 0 00.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        Atención 24/7
                    </a>
                </div>
            </div>
            <div class="lg:w-1/2 grid grid-cols-2 gap-4">
                <div class="row-span-2 rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1590490359854-dfba19688d73?auto=format&fit=crop&w=800&q=80" alt="Suite principal" class="h-full w-full object-cover">
                </div>
                <div class="rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=600&q=80" alt="Lobby" class="h-full w-full object-cover">
                </div>
                <div class="rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=600&q=80" alt="Área lounge" class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <section id="categorias" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
            <div>
                <h2 class="text-3xl font-['Playfair_Display'] font-semibold text-slate-900">Nuestras categorías de habitación</h2>
                <p class="mt-3 text-slate-500">Selecciona el tipo que más te guste. Confirmaremos una habitación disponible dentro de esa categoría para tus fechas.</p>
            </div>
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <span class="inline-flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                    Disponibilidad inmediata
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-rose-500"></span>
                    Fechas solicitadas llenas
                </span>
            </div>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($tiposHabitacion as $tipo)
                @php
                    $habitacionReferencia = $tipo->habitaciones->firstWhere('imagenPrincipal')
                        ?? $tipo->habitaciones->first(fn($habitacion) => $habitacion->imagenes->isNotEmpty())
                        ?? $tipo->habitaciones->first();

                    if ($habitacionReferencia?->imagenPrincipal) {
                        $imagenUrl = Storage::url($habitacionReferencia->imagenPrincipal->ruta_imagen);
                    } elseif ($habitacionReferencia?->imagenes->first()) {
                        $imagenUrl = Storage::url($habitacionReferencia->imagenes->first()->ruta_imagen);
                    } else {
                        $imagenUrl = 'https://images.unsplash.com/photo-1551888419-7ab9470cb3a7?auto=format&fit=crop&w=900&q=80';
                    }

                    $operativas = $tipo->habitaciones->filter(fn($habitacion) => $habitacion->estaOperativa());
                    $disponibles = $operativas->filter(fn($habitacion) => $habitacion->estadoEs('disponible'))->count();
                    $totalOperativas = $operativas->count();

                    if ($operativas->isEmpty()) {
                        $estadoBadge = ['texto' => 'En mantenimiento', 'clase' => 'bg-amber-500/90 text-white'];
                    } elseif ($disponibles > 0) {
                        $estadoBadge = ['texto' => $disponibles . ' disponibles', 'clase' => 'bg-emerald-500/90 text-white'];
                    } else {
                        $estadoBadge = ['texto' => 'Temporalmente sin disponibilidad', 'clase' => 'bg-rose-500/90 text-white'];
                    }

                    $amenidades = collect($tipo->habitaciones)
                        ->flatMap(fn($habitacion) => $habitacion->amenidades ?? [])
                        ->unique()
                        ->values()
                        ->take(4);

                    $amenidadesRestantes = max(0, collect($tipo->habitaciones)
                        ->flatMap(fn($habitacion) => $habitacion->amenidades ?? [])
                        ->unique()
                        ->count() - $amenidades->count());

                    $destinoReserva = auth()->check() && auth()->user()->esHuesped()
                        ? route('huesped.dashboard', ['tipo' => $tipo->id]) . '#reservar'
                        : (auth()->check() ? route('huesped.dashboard') : route('login'));

                    $ctaTexto = auth()->check() && auth()->user()->esHuesped() ? 'Reservar este tipo' : 'Inicia sesión para reservar';
                @endphp
                <article class="bg-white rounded-3xl shadow-lg overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition-transform">
                    <div class="relative h-56">
                        <img src="{{ $imagenUrl }}" alt="{{ $tipo->nombre }}" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/10 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-5 text-white flex items-end justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-indigo-200">{{ $tipo->nombre }}</p>
                                <h3 class="text-2xl font-semibold">Hasta {{ $tipo->capacidad }} huéspedes</h3>
                            </div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $estadoBadge['clase'] }} text-xs font-semibold">
                                {{ $estadoBadge['texto'] }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-500">Incluye {{ $tipo->habitaciones->count() }} habitación(es) dentro de la categoría.</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-400">Desde</p>
                                <p class="text-2xl font-semibold text-indigo-600">${{ number_format($tipo->precio_actual, 2) }}</p>
                                <p class="text-xs text-slate-400">por noche</p>
                            </div>
                        </div>
                        @if ($tipo->descripcion)
                            <p class="text-sm text-slate-600 leading-relaxed">{{ Str::limit($tipo->descripcion, 180) }}</p>
                        @else
                            <p class="text-sm text-slate-600 leading-relaxed">Una opción cómoda y elegante con amenidades cuidadosamente seleccionadas.</p>
                        @endif
                        <div class="flex flex-wrap gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Operativas: {{ $totalOperativas }}
                            </span>
                            @foreach ($amenidades as $amenidad)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75v10.5m5.25-5.25H6.75" />
                                    </svg>
                                    {{ $amenidad }}
                                </span>
                            @endforeach
                            @if ($amenidadesRestantes > 0)
                                <span class="inline-flex items-center px-3 py-1 bg-slate-100 rounded-full">+{{ $amenidadesRestantes }} amenidades</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-3 pt-2">
                            @if ($habitacionReferencia)
                                <a href="{{ route('habitaciones.show', $habitacionReferencia) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                                    Ver una habitación de ejemplo
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-xs text-slate-400">Próximamente habitaciones disponibles</span>
                            @endif
                            <a href="{{ $destinoReserva }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                {{ $ctaTexto }}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="sm:col-span-2 xl:col-span-3 text-center py-16 bg-white rounded-3xl shadow-inner">
                    <h3 class="text-xl font-semibold text-slate-900">Próximamente nuevas categorías</h3>
                    <p class="mt-3 text-sm text-slate-500">Estamos preparando espacios increíbles para ti. Vuelve pronto para descubrirlos.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section id="cta-reservar" class="bg-slate-900 text-white py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-3xl font-['Playfair_Display']">¿Listo para reservar?</h2>
            <p class="text-sm text-white/70">Inicia sesión como huésped para solicitar una reservación por categoría o crea una cuenta nueva para comenzar.</p>
            <div class="flex flex-wrap justify-center gap-4">
                @auth
                    @if (auth()->user()->esHuesped())
                        <a href="{{ route('huesped.dashboard') }}#reservar" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-indigo-700 font-semibold hover:bg-slate-100 transition">
                            Ir a mi panel de huésped
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-indigo-700 font-semibold hover:bg-slate-100 transition">
                            Iniciar sesión como huésped
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-indigo-700 font-semibold hover:bg-slate-100 transition">
                        Iniciar sesión
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-indigo-500 text-white font-semibold hover:bg-indigo-600 transition">
                        Crear cuenta
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>
@endsection
