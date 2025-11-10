@extends('layouts.public')

@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Storage;

    $imagenes = $habitacion->imagenes;
    $imagenPrincipal = $imagenes->firstWhere('es_principal', true) ?? $imagenes->first();
    $heroImage = $imagenPrincipal ? Storage::url($imagenPrincipal->ruta_imagen) : 'https://images.unsplash.com/photo-1551776235-dde6d4829808?auto=format&fit=crop&w=1600&q=80';

    $oldEntradaFormatted = optional(Carbon::make(old('fecha_entrada')))->translatedFormat('d M Y') ?? '';
    $oldSalidaFormatted = optional(Carbon::make(old('fecha_salida')))->translatedFormat('d M Y') ?? '';
@endphp

@section('content')
    <section class="relative bg-slate-900 text-white">
        <div class="absolute inset-0 opacity-35" style="background-image: url('{{ $heroImage }}'); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-slate-900/40"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="max-w-3xl space-y-4">
                <p class="uppercase text-sm tracking-[0.3em] text-white/70">{{ $habitacion->tipoHabitacion->nombre ?? 'Habitación' }}</p>
                <h1 class="text-4xl sm:text-5xl font-['Playfair_Display'] font-semibold">Habitación {{ $habitacion->numero }}</h1>
                @if ($habitacion->caracteristicas)
                    <p class="text-white/80 text-lg">{{ $habitacion->caracteristicas }}</p>
                @endif
                <div class="flex flex-wrap items-center gap-6 pt-4">
                    <div>
                        <p class="text-sm text-white/60">Tarifa actual</p>
                        <p class="text-3xl font-semibold">
                            ${{ number_format($habitacion->precio_actual, 2) }}
                            <span class="text-base font-normal">MXN / noche</span>
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-sm uppercase tracking-[0.2em]">
                        <span class="h-2.5 w-2.5 rounded-full
                            {{ $habitacion->estado === 'disponible' ? 'bg-emerald-400' : ($habitacion->estado === 'mantenimiento' ? 'bg-amber-400' : 'bg-slate-300') }}"></span>
                        {{ ucfirst($habitacion->estado) }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="grid lg:grid-cols-2 gap-10 p-6 sm:px-10 sm:py-10">
                {{-- Galería --}}
                <div data-carousel class="relative">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-3xl bg-slate-100">
                        @forelse ($imagenes as $index => $imagen)
                            <div data-carousel-slide class="absolute inset-0 transition-opacity duration-700 ease-out {{ $index === 0 ? 'opacity-100' : 'opacity-0 hidden' }}">
                                <img src="{{ Storage::url($imagen->ruta_imagen) }}" alt="Imagen {{ $index + 1 }} de la habitación {{ $habitacion->numero }}" class="w-full h-full object-cover">
                            </div>
                        @empty
                            <div class="absolute inset-0 flex items-center justify-center">
                                <img src="https://images.unsplash.com/photo-1505691723518-36a5ac3be353?auto=format&fit=crop&w=1200&q=80" alt="Habitación de hotel" class="w-full h-full object-cover">
                            </div>
                        @endforelse
                    </div>

                    @if ($imagenes->count() > 1)
                        <button type="button" data-carousel-prev class="absolute top-1/2 left-3 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg shadow-slate-900/10 text-slate-600 hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button type="button" data-carousel-next class="absolute top-1/2 right-3 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg shadow-slate-900/10 text-slate-600 hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5L15.75 12l-7.5 7.5" />
                            </svg>
                        </button>

                        <div class="mt-4 grid grid-cols-4 gap-3">
                            @foreach ($imagenes as $index => $imagen)
                                <button type="button" data-carousel-thumb="{{ $index }}" class="group relative overflow-hidden rounded-2xl border-2 border-transparent focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <img src="{{ Storage::url($imagen->ruta_imagen) }}" alt="Miniatura {{ $index + 1 }}" class="h-20 w-full object-cover transition group-hover:scale-105">
                                    <span class="pointer-events-none absolute inset-0 rounded-2xl bg-indigo-500/0 group-[.is-active]:bg-indigo-500/20 transition"></span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Detalles + Reserva --}}
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h2 class="text-2xl font-semibold text-slate-900">Detalles de la habitación</h2>
                        <p class="text-slate-600 leading-relaxed">
                            Disfruta de un ambiente sofisticado y acogedor diseñado para ofrecer descanso absoluto. Nuestra habitación ofrece acabados premium, ropa de cama hipoalergénica y servicios exclusivos para cada huésped.
                        </p>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Capacidad</p>
                                <p class="mt-1 text-lg font-semibold text-slate-800">Hasta {{ $habitacion->capacidad }} huéspedes</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tipo</p>
                                <p class="mt-1 text-lg font-semibold text-slate-800">{{ $habitacion->tipoHabitacion->nombre ?? 'Habitación' }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Estado</p>
                                <p class="mt-1 text-lg font-semibold capitalize text-slate-800">{{ $habitacion->estado }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Número</p>
                                <p class="mt-1 text-lg font-semibold text-slate-800">{{ $habitacion->numero }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Bloque de reserva / acciones --}}
                    <div class="rounded-3xl bg-indigo-50 border border-indigo-100 p-6 space-y-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-indigo-600 text-white flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21M9 9.75l3 3 3-3m-3 3V3" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-indigo-500">Reserva tu estancia</p>
                                <p class="text-lg font-semibold text-indigo-900">Atención personalizada 24/7</p>
                            </div>
                        </div>

                        @guest
                            <p class="text-sm text-indigo-900/80">Inicia sesión para confirmar tu reserva.</p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                    Iniciar sesión
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                                <a href="tel:+525512345678" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white text-indigo-600 text-sm font-semibold border border-indigo-100 hover:border-indigo-200 transition">
                                    Llamar a recepción
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97a1.125 1.125 0 00.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                </a>
                            </div>
                        @else
                            @if(auth()->user()->esAdministrador() || auth()->user()->esGerente() || auth()->user()->esRecepcionista())
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                        Ir a tu panel
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </a>
                                </div>
                            @else
                                {{-- Formulario de reservación para huésped --}}
                                <form action="{{ route('reservaciones.store') }}" method="POST" id="form-reserva" class="space-y-4">
                                    @csrf
                                    <input type="hidden" name="habitacion_id" value="{{ $habitacion->id }}">
                                    <div class="grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-indigo-900 mb-1">Personas</label>
                                            <input type="number" name="numero_huespedes" id="numero_huespedes"
                                                   min="1" max="{{ $habitacion->capacidad }}" value="{{ old('numero_huespedes', 1) }}"
                                                   class="w-full rounded-xl border border-indigo-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                                   {{ $habitacion->estado === 'mantenimiento' ? 'disabled' : '' }} required>
                                            <small class="text-indigo-900/70">Capacidad máx: {{ $habitacion->capacidad }}</small>
                                            @error('numero_huespedes') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-indigo-900 mb-1">Fechas</label>
                                            <div class="grid sm:grid-cols-2 gap-3">
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-3 flex items-center text-indigo-400 pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M5.25 7.5h13.5A1.5 1.5 0 0120.25 9v9.75A1.5 1.5 0 0118.75 20.25H5.25A1.5 1.5 0 013.75 18.75V9A1.5 1.5 0 015.25 7.5zM8.25 12.75h.008v.008H8.25v-.008zM8.25 15.75h.008v.008H8.25v-.008zM11.25 12.75h.008v.008h-.008v-.008z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" id="fecha_entrada_visible" value="{{ $oldEntradaFormatted }}" placeholder="Fecha de llegada" class="w-full rounded-xl border border-indigo-100 px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                                           autocomplete="off" readonly {{ $habitacion->estado === 'mantenimiento' ? 'disabled' : '' }}>
                                                </div>
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-3 flex items-center text-indigo-400 pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M5.25 7.5h13.5A1.5 1.5 0 0120.25 9v9.75A1.5 1.5 0 0118.75 20.25H5.25A1.5 1.5 0 013.75 18.75V9A1.5 1.5 0 015.25 7.5zM8.25 12.75h.008v.008H8.25v-.008zM8.25 15.75h.008v.008H8.25v-.008zM11.25 12.75h.008v.008h-.008v-.008z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" id="fecha_salida_visible" value="{{ $oldSalidaFormatted }}" placeholder="Fecha de salida" class="w-full rounded-xl border border-indigo-100 px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                                           autocomplete="off" readonly {{ $habitacion->estado === 'mantenimiento' ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                            <input type="hidden" name="fecha_entrada" id="fecha_entrada" value="{{ old('fecha_entrada') }}">
                                            <input type="hidden" name="fecha_salida" id="fecha_salida" value="{{ old('fecha_salida') }}">
                                            <input type="text" id="rango-fechas" class="hidden" {{ $habitacion->estado === 'mantenimiento' ? 'disabled' : '' }}>
                                            @error('fecha_entrada') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                                            @error('fecha_salida') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-indigo-900/70">
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500/70"></span>
                                                    Disponible
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500/80"></span>
                                                    Mantenimiento
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500/80"></span>
                                                    Ocupada
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid sm:grid-cols-3 gap-4">
                                        <div class="rounded-2xl bg-white border border-indigo-100 p-4">
                                            <p class="text-xs uppercase tracking-[0.2em] text-indigo-500">Noches</p>
                                            <p class="mt-1 text-xl font-semibold text-indigo-900"><span id="noches">0</span></p>
                                        </div>
                                        <div class="rounded-2xl bg-white border border-indigo-100 p-4">
                                            <p class="text-xs uppercase tracking-[0.2em] text-indigo-500">Tarifa por noche</p>
                                            <p class="mt-1 text-xl font-semibold text-indigo-900">${{ number_format($habitacion->precio_actual, 2) }} MXN</p>
                                        </div>
                                        <div class="rounded-2xl bg-white border border-indigo-100 p-4">
                                            <p class="text-xs uppercase tracking-[0.2em] text-indigo-500">Estimado total</p>
                                            <p class="mt-1 text-xl font-semibold text-indigo-900">$<span id="precio_estimado">0.00</span> MXN</p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-indigo-900 mb-1">Notas (opcional)</label>
                                        <textarea name="notas" rows="3" class="w-full rounded-xl border border-indigo-100 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300" placeholder="¿Alguna solicitud especial?">{{ old('notas') }}</textarea>
                                        @error('notas') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3">
                                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition"
                                            {{ $habitacion->estado === 'mantenimiento' ? 'disabled' : '' }}>
                                            Confirmar reservación
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                            </svg>
                                        </button>

                                        <a href="{{ route('huesped.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold hover:bg-indigo-200 transition">
                                            Ver mis reservaciones
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m6.75-2.25v12A2.25 2.25 0 0119.5 21H4.5A2.25 2.25 0 012.25 18V6A2.25 2.25 0 014.5 3.75h15A2.25 2.25 0 0121.75 6z" />
                                            </svg>
                                        </a>

                                        <a href="tel:+525512345678" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white text-indigo-600 text-sm font-semibold border border-indigo-100 hover:border-indigo-200 transition">
                                            Llamar a recepción
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97a1.125 1.125 0 00.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                            </svg>
                                        </a>
                                    </div>

                                    @if ($habitacion->estado === 'mantenimiento')
                                        <p class="text-amber-700 bg-amber-100 border border-amber-200 rounded-xl px-3 py-2 text-sm inline-block">
                                            Esta habitación está en mantenimiento y no se puede reservar por ahora.
                                        </p>
                                    @endif
                                    @error('habitacion_id')
                                        <div class="text-red-600 text-sm">{{ $message }}</div>
                                    @enderror
                                </form>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Servicios --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-slate-900 rounded-3xl overflow-hidden">
            <div class="grid lg:grid-cols-2">
                <div class="p-8 sm:p-12 space-y-4 text-white">
                    <h2 class="text-3xl font-['Playfair_Display'] font-semibold">Servicios pensados para ti</h2>
                    <p class="text-white/70">Acceso al spa, gimnasio 24 horas, servicio a la habitación gourmet y concierge personalizado. Complementa tu estancia con experiencias únicas en la ciudad.</p>
                    <ul class="space-y-3 text-white/80 text-sm">
                        <li class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </span>
                            Traslado al aeropuerto (con costo adicional)
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </span>
                            Experiencias gastronómicas exclusivas
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.5c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v4.5h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                                </svg>
                            </span>
                            Ubicación privilegiada en el corazón de la ciudad
                        </li>
                    </ul>
                </div>
                <div class="hidden lg:block relative">
                    <img src="https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1200&q=80" alt="Servicios premium" class="absolute inset-0 h-full w-full object-cover">
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    @once('flatpickr-css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endonce
    <style>
        .flatpickr-day.is-disponible,
        .flatpickr-day.is-disponible.flatpickr-disabled {
            background-color: rgba(16, 185, 129, 0.12) !important;
            color: #0f172a !important;
            border-radius: 6px;
        }

        .flatpickr-day.is-disponible:hover,
        .flatpickr-day.is-disponible:focus {
            background-color: rgba(16, 185, 129, 0.28) !important;
        }

        .flatpickr-day.is-ocupada,
        .flatpickr-day.is-ocupada.flatpickr-disabled,
        .flatpickr-day.is-ocupada:hover,
        .flatpickr-day.is-ocupada:focus {
            background-color: #ef4444 !important;
            color: #fff !important;
            border-radius: 6px;
        }

        .flatpickr-day.is-mantenimiento,
        .flatpickr-day.is-mantenimiento.flatpickr-disabled,
        .flatpickr-day.is-mantenimiento:hover,
        .flatpickr-day.is-mantenimiento:focus {
            background-color: #f59e0b !important;
            color: #0f172a !important;
            border-radius: 6px;
        }
    </style>
@endpush

@push('scripts')
    @once('flatpickr-lib')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endonce

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.flatpickr) {
                return;
            }

            // Carousel
            document.querySelectorAll('[data-carousel]').forEach((carousel) => {
                const slides = carousel.querySelectorAll('[data-carousel-slide]');
                if (!slides.length) return;

                let current = 0;
                const updateSlides = () => {
                    slides.forEach((slide, index) => {
                        if (index === current) {
                            slide.classList.remove('hidden');
                            requestAnimationFrame(() => {
                                slide.classList.add('opacity-100');
                                slide.classList.remove('opacity-0');
                            });
                        } else {
                            slide.classList.add('opacity-0');
                            slide.classList.remove('opacity-100');
                            slide.addEventListener('transitionend', function handler() {
                                slide.classList.add('hidden');
                                slide.removeEventListener('transitionend', handler);
                            }, { once: true });
                        }
                    });

                    const thumbs = carousel.querySelectorAll('[data-carousel-thumb]');
                    thumbs.forEach((thumb, index) => {
                        if (index === current) {
                            thumb.classList.add('is-active', 'border-indigo-500');
                        } else {
                            thumb.classList.remove('is-active', 'border-indigo-500');
                        }
                    });
                };
                carousel.querySelector('[data-carousel-next]')?.addEventListener('click', () => {
                    current = (current + 1) % slides.length; updateSlides();
                });
                carousel.querySelector('[data-carousel-prev]')?.addEventListener('click', () => {
                    current = (current - 1 + slides.length) % slides.length; updateSlides();
                });
                carousel.querySelectorAll('[data-carousel-thumb]').forEach((thumb, index) => {
                    thumb.addEventListener('click', () => { current = index; updateSlides(); });
                });
                updateSlides();
            });

            // Reserva (solo si existe el input rango-fechas, es decir, si se muestra el formulario)
            const rango = document.getElementById('rango-fechas');
            if (rango && !rango.disabled) {
                const capacidadMax   = {{ (int) $habitacion->capacidad }};
                const precioNoche    = {{ (float) $habitacion->precio_actual }};
                const inpPersonas    = document.getElementById('numero_huespedes');
                const nochesSpan     = document.getElementById('noches');
                const precioSpan     = document.getElementById('precio_estimado');
                const fechaIn        = document.getElementById('fecha_entrada');
                const fechaOut       = document.getElementById('fecha_salida');
                const entradaVisible = document.getElementById('fecha_entrada_visible');
                const salidaVisible  = document.getElementById('fecha_salida_visible');

                if (inpPersonas) {
                    inpPersonas.setAttribute('max', capacidadMax);
                }

                const disponibilidadActual = { bloques: [] };
                let fpInstance = null;

                const isValidDate = (date) => date instanceof Date && !Number.isNaN(date.getTime());
                const formatDisplay = (date) => date.toLocaleDateString('es-MX', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                const updateResumen = (startDate, endDate) => {
                    const inicioValido = isValidDate(startDate);
                    const finValido = isValidDate(endDate);

                    if (entradaVisible) {
                        entradaVisible.value = inicioValido ? formatDisplay(startDate) : '';
                    }

                    if (salidaVisible) {
                        salidaVisible.value = finValido ? formatDisplay(endDate) : '';
                    }

                    if (!inicioValido || !finValido) {
                        nochesSpan.textContent = '0';
                        precioSpan.textContent = '0.00';
                        return;
                    }

                    const diff = Math.max(0, Math.round((endDate - startDate) / (1000 * 60 * 60 * 24)));
                    nochesSpan.textContent = diff;
                    const total = diff > 0 ? diff * precioNoche : 0;
                    precioSpan.textContent = total.toFixed(2);
                };

                const openCalendar = (event) => {
                    event?.preventDefault();
                    if (fpInstance) {
                        fpInstance.open();
                    }
                };

                [entradaVisible, salidaVisible].forEach((input) => {
                    if (!input) {
                        return;
                    }

                    input.addEventListener('click', openCalendar);
                    input.addEventListener('focus', openCalendar);
                    input.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            openCalendar();
                        }
                    });
                });

                const decorateDay = (dayElem) => {
                    const date = dayElem.dateObj.toISOString().slice(0, 10);
                    const bloque = (disponibilidadActual.bloques || []).find((b) => date >= b.from && date <= b.to);

                    dayElem.classList.remove('is-ocupada', 'is-mantenimiento', 'is-disponible');
                    dayElem.style.borderRadius = '6px';

                    const baseLabel = dayElem.dataset.baseLabel || dayElem.getAttribute('aria-label') || '';
                    dayElem.dataset.baseLabel = baseLabel;

                    if (bloque) {
                        dayElem.classList.add(`is-${bloque.estado}`);
                        const estadoTexto = bloque.estado === 'ocupada' ? 'Ocupada' : 'Mantenimiento';
                        dayElem.setAttribute('aria-label', `${baseLabel} – ${estadoTexto}`);
                    } else {
                        dayElem.classList.add('is-disponible');
                        dayElem.setAttribute('aria-label', baseLabel);
                    }
                };

                const inicializarCalendario = (bloques) => {
                    disponibilidadActual.bloques = bloques || [];
                    const disabled = disponibilidadActual.bloques.map((b) => ({ from: b.from, to: b.to }));
                    const defaultRange = (fechaIn.value && fechaOut.value) ? [fechaIn.value, fechaOut.value] : null;

                    if (!fpInstance) {
                        fpInstance = flatpickr(rango, {
                            mode: 'range',
                            dateFormat: 'Y-m-d',
                            minDate: 'today',
                            clickOpens: false,
                            disable: disabled,
                            defaultDate: defaultRange,
                            onReady: (selectedDates, dateStr, instance) => {
                                if (selectedDates.length === 2) {
                                    updateResumen(selectedDates[0], selectedDates[1]);
                                } else if (defaultRange && defaultRange.length === 2) {
                                    updateResumen(new Date(defaultRange[0]), new Date(defaultRange[1]));
                                } else {
                                    updateResumen(null, null);
                                }
                                instance.calendarContainer.classList.add('rounded-xl');
                            },
                            onChange: (dates) => {
                                if (dates.length === 2) {
                                    const [start, end] = dates;
                                    const entrada = start.toISOString().slice(0, 10);
                                    const salida = end.toISOString().slice(0, 10);
                                    fechaIn.value = entrada;
                                    fechaOut.value = salida;
                                    updateResumen(start, end);
                                } else {
                                    fechaIn.value = '';
                                    fechaOut.value = '';
                                    updateResumen(null, null);
                                }
                            },
                            onDayCreate: function (_, __, ___, dayElem) {
                                decorateDay(dayElem);
                            }
                        });
                    } else {
                        fpInstance.set('disable', disabled);
                        fpInstance.redraw();
                    }

                    if (!defaultRange) {
                        updateResumen(null, null);
                    }
                };

                const endpoint = @json(route('habitaciones.disponibilidad', $habitacion));
                fetch(endpoint)
                    .then((r) => r.json())
                    .then((data) => {
                        inicializarCalendario(data.bloques || []);
                    })
                    .catch(() => {
                        inicializarCalendario([]);
                    });
            }
        });
    </script>
@endpush
