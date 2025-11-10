@extends('layouts.public')

@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
    $estadoPill = [
        'confirmada' => 'bg-emerald-50 text-emerald-600',
        'activa' => 'bg-indigo-50 text-indigo-600',
        'cancelada' => 'bg-slate-100 text-slate-500',
    ];
    $estadoDot = [
        'confirmada' => 'bg-emerald-500',
        'activa' => 'bg-indigo-500',
        'cancelada' => 'bg-slate-400',
    ];
@endphp

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-indigo-700 text-white">
        <div class="absolute inset-0 opacity-40" style="background-image: url('https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center;"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 flex flex-col lg:flex-row gap-10 items-center">
            <div class="lg:w-1/2 space-y-6">
                <p class="uppercase text-sm tracking-[0.3em] text-white/70">Bienvenido a Hotel Aurora</p>
                <h1 class="text-4xl sm:text-5xl font-['Playfair_Display'] font-semibold leading-tight">Elegancia, confort y experiencias inolvidables</h1>
                <p class="text-white/80 text-lg">Descubre nuestras habitaciones diseñadas para tu descanso. Vive el lujo en cada detalle y permite que nuestro equipo se encargue de crear recuerdos extraordinarios.</p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="#habitaciones" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-900/30 hover:bg-slate-100 transition">
                        Explorar habitaciones
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                    <a href="tel:+525512345678" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition">
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

    @auth
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white/95 rounded-3xl shadow-xl border border-white/40 backdrop-blur p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-900/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-indigo-500">Tu estancia</p>
                            <h2 class="text-xl font-semibold text-slate-900">Planifica y consulta tus reservaciones</h2>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed">Explora las habitaciones disponibles, confirma tu estadía directamente desde esta página y mantente al tanto de tus próximas experiencias en Hotel Aurora.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#habitaciones" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                            Reservar una habitación
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <a href="#mis-reservas" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-indigo-600 text-sm font-semibold border border-indigo-100 hover:border-indigo-200 transition">
                            Ver mis reservaciones
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l1.5 1.5L9 4.5m0 0L7.5 3 3 7.5M9 4.5h9.75A1.5 1.5 0 0120.25 6v12a1.5 1.5 0 01-1.5 1.5h-12A1.5 1.5 0 015.25 18V9" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="bg-white/95 rounded-3xl shadow-xl border border-white/40 backdrop-blur p-6 sm:p-8">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Tu próxima reservación</h3>
                    @if ($proximaReservacion)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm uppercase tracking-[0.2em] text-indigo-500">{{ $proximaReservacion->habitacion->tipoHabitacion->nombre ?? 'Habitación' }}</p>
                                    <p class="text-xl font-semibold text-slate-900">Habitación {{ $proximaReservacion->habitacion->numero }}</p>
                                </div>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                    {{ ucfirst($proximaReservacion->estado) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm text-slate-600">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Check-in</p>
                                    <p class="mt-1 text-base font-semibold text-slate-800">{{ Carbon::parse($proximaReservacion->fecha_entrada)->translatedFormat('d \d\e F Y') }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Check-out</p>
                                    <p class="mt-1 text-base font-semibold text-slate-800">{{ Carbon::parse($proximaReservacion->fecha_salida)->translatedFormat('d \d\e F Y') }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Huéspedes</p>
                                    <p class="mt-1 text-base font-semibold text-slate-800">{{ $proximaReservacion->numero_huespedes }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total</p>
                                    <p class="mt-1 text-base font-semibold text-slate-800">${{ number_format($proximaReservacion->precio_total, 2) }} MXN</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-start gap-4 text-sm text-slate-600">
                            <p>Aún no tienes una reservación confirmada. Elige tu habitación favorita y completa el formulario para asegurar tu próxima estancia.</p>
                            <a href="#habitaciones" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                Buscar habitaciones disponibles
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endauth

    <section id="habitaciones" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
            <div>
                <h2 class="text-3xl font-['Playfair_Display'] font-semibold text-slate-900">Nuestras habitaciones</h2>
                <p class="mt-3 text-slate-500">Elige el espacio perfecto para tu estancia, cada habitación cuenta con amenidades únicas y un ambiente sofisticado.</p>
            </div>
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <span class="inline-flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                    Disponibles ahora
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-amber-500"></span>
                    Alta demanda
                </span>
            </div>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($habitaciones as $habitacion)
                @php
                    $imagenPrincipal = $habitacion->imagenPrincipal ?? $habitacion->imagenes->first();
                    $imagenUrl = $imagenPrincipal ? Storage::url($imagenPrincipal->ruta_imagen) : 'https://images.unsplash.com/photo-1551888419-7ab9470cb3a7?auto=format&fit=crop&w=800&q=80';
                    $estadoColor = match($habitacion->estado) {
                        'disponible' => 'bg-emerald-500/90 text-white',
                        'ocupada' => 'bg-rose-500/90 text-white',
                        'mantenimiento' => 'bg-amber-500/90 text-white',
                        'limpieza' => 'bg-sky-500/90 text-white',
                        default => 'bg-slate-200 text-slate-700',
                    };
                @endphp
                <article class="bg-white rounded-3xl shadow-lg overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition-transform">
                    <a href="{{ route('habitaciones.show', $habitacion) }}" class="relative block h-56">
                        <img src="{{ $imagenUrl }}" alt="Habitación {{ $habitacion->numero }}" class="w-full h-full object-cover">
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide {{ $estadoColor }}">{{ ucfirst($habitacion->estado) }}</span>
                    </a>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-indigo-500 font-medium">{{ $habitacion->tipoHabitacion->nombre ?? 'Habitación' }}</p>
                                <h3 class="text-xl font-semibold text-slate-900">Habitación {{ $habitacion->numero }}</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-400">Desde</p>
                                <p class="text-2xl font-semibold text-indigo-600">${{ number_format($habitacion->precio_actual, 2) }}</p>
                                <p class="text-xs text-slate-400">por noche</p>
                            </div>
                        </div>
                        @if ($habitacion->caracteristicas)
                            <p class="text-sm text-slate-500">{{ Str::limit($habitacion->caracteristicas, 160) }}</p>
                        @endif
                        <div class="flex flex-wrap gap-2 text-xs text-slate-500">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3.75h4.5M3 6h18v12H3z" />
                                </svg>
                                Capacidad: {{ $habitacion->capacidad }}
                            </span>
                            @foreach (collect($habitacion->amenidades ?? [])->take(3) as $amenidad)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75v10.5m5.25-5.25H6.75" />
                                    </svg>
                                    {{ $amenidad }}
                                </span>
                            @endforeach
                            @if (collect($habitacion->amenidades ?? [])->count() > 3)
                                <span class="inline-flex items-center px-3 py-1 bg-slate-100 rounded-full">+{{ collect($habitacion->amenidades)->count() - 3 }} amenidades</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-3 pt-2">
                            <a href="{{ route('habitaciones.show', $habitacion) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                                Ver detalles
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                            @auth
                                <a href="{{ route('habitaciones.show', $habitacion) }}#reservar" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                    Reservar ahora
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                    Reservar ahora
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
                            @endauth
                        </div>
                    </div>
                </article>
            @empty
                <div class="sm:col-span-2 xl:col-span-3 text-center py-16 bg-white rounded-3xl shadow-inner">
                    <h3 class="text-xl font-semibold text-slate-900">Próximamente habitaciones disponibles</h3>
                    <p class="mt-3 text-sm text-slate-500">Estamos preparando nuevas experiencias para ti. Vuelve pronto para descubrir nuestras habitaciones.</p>
                </div>
            @endforelse
        </div>
    </section>

    @auth
        <section id="mis-reservas" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="px-6 sm:px-10 py-8 border-b border-slate-100">
                    <h2 class="text-2xl font-['Playfair_Display'] font-semibold text-slate-900">Mis reservaciones</h2>
                    <p class="mt-2 text-sm text-slate-500">Consulta el estado y los detalles de tus próximas estancias.</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($reservaciones as $reservacion)
                        <article class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 px-6 sm:px-10 py-6">
                            <div class="flex items-center gap-5">
                                @php
                                    $imagen = $reservacion->habitacion->imagenPrincipal ? Storage::url($reservacion->habitacion->imagenPrincipal->ruta_imagen) : 'https://images.unsplash.com/photo-1551888419-7ab9470cb3a7?auto=format&fit=crop&w=400&q=80';
                                @endphp
                                <div class="h-20 w-20 rounded-2xl overflow-hidden">
                                    <img src="{{ $imagen }}" alt="Habitación {{ $reservacion->habitacion->numero }}" class="h-full w-full object-cover">
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-500">{{ $reservacion->habitacion->tipoHabitacion->nombre ?? 'Habitación' }}</p>
                                    <h3 class="text-lg font-semibold text-slate-900">Habitación {{ $reservacion->habitacion->numero }}</h3>
                                    <p class="text-sm text-slate-500">Del {{ Carbon::parse($reservacion->fecha_entrada)->translatedFormat('d \d\e F') }} al {{ Carbon::parse($reservacion->fecha_salida)->translatedFormat('d \d\e F Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Estado</p>
                                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{ $estadoPill[$reservacion->estado] ?? 'bg-amber-50 text-amber-600' }}">
                                        <span class="h-2 w-2 rounded-full {{ $estadoDot[$reservacion->estado] ?? 'bg-amber-500' }}"></span>
                                        {{ ucfirst($reservacion->estado) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total</p>
                                    <p class="text-lg font-semibold text-slate-900">${{ number_format($reservacion->precio_total, 2) }} MXN</p>
                                </div>
                                @if (in_array($reservacion->estado, ['pendiente', 'confirmada']) && Carbon::parse($reservacion->fecha_entrada)->isFuture())
                                    <form method="POST" action="{{ route('reservaciones.destroy', $reservacion) }}" onsubmit="return confirm('¿Deseas cancelar tu reservación?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-rose-200 text-rose-600 text-sm font-semibold hover:bg-rose-50 transition">
                                            Cancelar
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="px-6 sm:px-10 py-10 text-center text-sm text-slate-500">
                            Aún no registras reservaciones. Selecciona una habitación y confirma tu estancia para verla aquí.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    @endauth
@endsection
