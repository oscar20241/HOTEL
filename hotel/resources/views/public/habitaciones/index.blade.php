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
                <img src="" alt="">
                <p class="uppercase text-sm tracking-[0.3em] text-white/70">Bienvenido a Hotel PASA EL EXTRA Inn</p>
                <h1 class="text-4xl sm:text-5xl font-semibold leading-tight">ELEGANCIA, CONFORT Y EXPERIENCIAS INOLVIDABLES</h1>
                <p class="text-white/80 text-lg">Descubre nuestras habitaciones diseñadas especialmente para brindarte el descanso que mereces. Sumérgete en un ambiente de confort y tranquilidad, donde cada detalle ha sido cuidadosamente pensado para ofrecerte una experiencia única.</p>
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
            <div class="lg:w-1/2 grid grid-cols-2 gap-5">
                <div class="row-span-2 rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://plus.unsplash.com/premium_photo-1661879252375-7c1db1932572?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Suite principal" class="h-full w-full object-cover">
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

    <section id="habitaciones" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-12">
            <div>
                <h2 class="text-3xl font-semibold text-slate-900">Nuestras habitaciones</h2>
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
                    $puedeReservar = auth()->check() && auth()->user()->esHuesped();
                    $precioNoche = number_format($habitacion->precio_actual, 2, '.', '');
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
                            @if ($puedeReservar)
                                <button type="button"
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition"
                                        data-modal-open
                                        data-habitacion-id="{{ $habitacion->id }}"
                                        data-habitacion-numero="{{ $habitacion->numero }}"
                                        data-habitacion-tipo="{{ $habitacion->tipoHabitacion->nombre ?? 'Habitación' }}"
                                        data-habitacion-capacidad="{{ $habitacion->capacidad }}"
                                        data-habitacion-precio="{{ $precioNoche }}"
                                        data-habitacion-estado="{{ $habitacion->estado }}"
                                        data-habitacion-imagen="{{ $imagenUrl }}"
                                        data-disponibilidad-url="{{ route('habitaciones.disponibilidad', $habitacion) }}">
                                    Reservar ahora
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                    Reservar ahora
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </a>
                            @endif
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
        @if (auth()->user()->esHuesped())
            <div id="modal-reserva" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-habitacion-titulo">
                <div data-modal-overlay class="absolute inset-0 bg-slate-900/70 backdrop-blur"></div>
                <div class="relative mx-auto mt-10 w-full max-w-4xl px-4 py-6 sm:py-10">
                    <div class="rounded-3xl bg-white shadow-2xl ring-1 ring-slate-900/5 overflow-hidden">
                        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-indigo-500">Reserva tu habitación</p>
                                <h2 id="modal-habitacion-titulo" class="text-2xl font-semibold text-slate-900">Habitación</h2>
                            </div>
                            <button type="button" data-modal-close class="text-slate-400 hover:text-slate-600 transition" aria-label="Cerrar reserva">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid gap-8 lg:grid-cols-[320px,1fr] px-6 pb-6 pt-6 sm:px-8">
                            <div class="space-y-5">
                                <div class="overflow-hidden rounded-2xl bg-slate-100">
                                    <img id="modal-habitacion-imagen" src="https://images.unsplash.com/photo-1551888419-7ab9470cb3a7?auto=format&fit=crop&w=800&q=80" alt="Habitación" class="h-48 w-full object-cover">
                                </div>
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span id="modal-habitacion-tipo" class="text-sm font-semibold text-indigo-600">Habitación</span>
                                        <span id="modal-estado-badge" class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            <span class="estado-dot h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                            <span id="modal-estado-texto" class="capitalize">Disponible</span>
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 text-sm text-slate-600">
                                        <div class="space-y-1">
                                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Capacidad</p>
                                            <p><span id="modal-capacidad-texto">0</span> huéspedes</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Tarifa</p>
                                            <p>$<span id="modal-tarifa">0.00</span> MXN / noche</p>
                                        </div>
                                    </div>
                                </div>
                                <div id="modal-alerta" class="hidden rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"></div>
                            </div>

                            <form action="{{ route('reservaciones.store') }}" method="POST" id="modal-form-reserva" class="space-y-5">
                                @csrf
                                <input type="hidden" name="habitacion_id" id="modal-habitacion-id">
                                <input type="hidden" name="fecha_entrada" id="modal-fecha-entrada">
                                <input type="hidden" name="fecha_salida" id="modal-fecha-salida">

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="modal-numero-huespedes" class="mb-1 block text-sm font-medium text-slate-700">Personas</label>
                                        <input type="number" name="numero_huespedes" id="modal-numero-huespedes" min="1" value="1" required class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="mt-1 text-xs text-slate-500">Capacidad máxima: <span id="modal-capacidad-max">0</span></p>
                                    </div>
                                    <div>
                                        <label for="modal-rango-fechas" class="mb-1 block text-sm font-medium text-slate-700">Fechas (entrada / salida)</label>
                                        <input type="text" id="modal-rango-fechas" placeholder="Selecciona fechas" class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off" required>
                                        <div class="mt-2 flex flex-wrap items-center gap-3 text-[11px] text-slate-500">
                                            <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Disponible</span>
                                            <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-amber-500"></span>Mantenimiento</span>
                                            <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-rose-500"></span>Ocupada</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-3">
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Noches</p>
                                        <p class="mt-2 text-xl font-semibold text-slate-900"><span id="modal-noches">0</span></p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Tarifa por noche</p>
                                        <p class="mt-2 text-xl font-semibold text-slate-900">$<span id="modal-tarifa-resumen">0.00</span> MXN</p>
                                    </div>
                                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                                        <p class="text-xs uppercase tracking-[0.3em] text-indigo-500">Total estimado</p>
                                        <p class="mt-2 text-xl font-semibold text-indigo-900">$<span id="modal-total">0.00</span> MXN</p>
                                    </div>
                                </div>

                                <div>
                                    <label for="modal-notas" class="mb-1 block text-sm font-medium text-slate-700">Notas (opcional)</label>
                                    <textarea name="notas" id="modal-notas" rows="3" class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500" placeholder="¿Alguna solicitud especial?"></textarea>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" data-modal-submit class="inline-flex items-center gap-2 rounded-full bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                        Confirmar reservación
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                        </svg>
                                    </button>
                                    <button type="button" data-modal-close class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:border-slate-300">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-day.is-disponible {
            background-color: rgba(16, 185, 129, 0.15);
        }

        .flatpickr-day.is-ocupada,
        .flatpickr-day.is-ocupada:hover,
        .flatpickr-day.is-ocupada:focus {
            background-color: #ef4444 !important;
            color: #fff !important;
        }

        .flatpickr-day.is-mantenimiento,
        .flatpickr-day.is-mantenimiento:hover,
        .flatpickr-day.is-mantenimiento:focus {
            background-color: #f59e0b !important;
            color: #0f172a !important;
        }
    </style>
@endpush

@push('scripts')
    @once('flatpickr-lib')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @endonce

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modal-reserva');
            if (!modal || !window.flatpickr) {
                return;
            }

            const overlay = modal.querySelector('[data-modal-overlay]');
            const closeButtons = modal.querySelectorAll('[data-modal-close]');
            const form = document.getElementById('modal-form-reserva');
            const submitButton = modal.querySelector('[data-modal-submit]');
            const rangoInput = document.getElementById('modal-rango-fechas');
            const fechaEntradaInput = document.getElementById('modal-fecha-entrada');
            const fechaSalidaInput = document.getElementById('modal-fecha-salida');
            const nochesSpan = document.getElementById('modal-noches');
            const totalSpan = document.getElementById('modal-total');
            const tarifaResumenSpan = document.getElementById('modal-tarifa-resumen');
            const tarifaSpan = document.getElementById('modal-tarifa');
            const personasInput = document.getElementById('modal-numero-huespedes');
            const capacidadTexto = document.getElementById('modal-capacidad-texto');
            const capacidadMaxTexto = document.getElementById('modal-capacidad-max');
            const imagen = document.getElementById('modal-habitacion-imagen');
            const tipoSpan = document.getElementById('modal-habitacion-tipo');
            const titulo = document.getElementById('modal-habitacion-titulo');
            const estadoBadge = document.getElementById('modal-estado-badge');
            const estadoDot = estadoBadge.querySelector('.estado-dot');
            const estadoTexto = document.getElementById('modal-estado-texto');
            const alerta = document.getElementById('modal-alerta');
            const habitacionIdInput = document.getElementById('modal-habitacion-id');
            const notas = document.getElementById('modal-notas');

            let nightlyRate = 0;
            let disponibilidadActual = { bloques: [] };
            let fpInstance = null;
            let disponibilidadUrl = null;

            const estadoClasses = {
                disponible: {
                    badge: 'bg-emerald-100 text-emerald-700',
                    dot: 'bg-emerald-500',
                    mensaje: '',
                    bloquea: false,
                },
                ocupada: {
                    badge: 'bg-rose-100 text-rose-700',
                    dot: 'bg-rose-500',
                    mensaje: 'Esta habitación está ocupada actualmente. Selecciona otras fechas disponibles.',
                    bloquea: false,
                },
                mantenimiento: {
                    badge: 'bg-amber-100 text-amber-700',
                    dot: 'bg-amber-500',
                    mensaje: 'Esta habitación se encuentra en mantenimiento y no puede reservarse por ahora.',
                    bloquea: true,
                },
                limpieza: {
                    badge: 'bg-sky-100 text-sky-700',
                    dot: 'bg-sky-500',
                    mensaje: 'Esta habitación está en limpieza. Prueba con otras fechas o intenta más tarde.',
                    bloquea: false,
                },
            };

            const actualizarEstadoVisual = (estado) => {
                const info = estadoClasses[estado] || estadoClasses.disponible;
                estadoBadge.className = `inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ${info.badge}`;
                estadoDot.className = `estado-dot h-2.5 w-2.5 rounded-full ${info.dot}`;
                estadoTexto.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
                alerta.textContent = info.mensaje;
                alerta.classList.toggle('hidden', !info.mensaje);
                const disabled = info.bloquea ?? false;
                [personasInput, rangoInput, notas, submitButton].forEach((el) => {
                    el.disabled = disabled;
                    if (disabled && el === submitButton) {
                        submitButton.classList.add('cursor-not-allowed', 'opacity-70');
                    } else if (!disabled && el === submitButton) {
                        submitButton.classList.remove('cursor-not-allowed', 'opacity-70');
                    }
                });
                if (disabled && fpInstance) {
                    fpInstance.clear();
                    fechaEntradaInput.value = '';
                    fechaSalidaInput.value = '';
                    actualizarResumen(null, null);
                }
            };

            const actualizarResumen = (startDate, endDate) => {
                if (!(startDate instanceof Date) || !(endDate instanceof Date)) {
                    nochesSpan.textContent = '0';
                    totalSpan.textContent = '0.00';
                    return;
                }

                const diff = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
                nochesSpan.textContent = diff;
                const total = diff > 0 ? diff * nightlyRate : 0;
                totalSpan.textContent = total.toFixed(2);
            };

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

            const inicializarCalendario = (bloques, defaultRange = null) => {
                disponibilidadActual = { bloques: bloques || [] };
                const disabled = (disponibilidadActual.bloques || []).map((b) => ({ from: b.from, to: b.to }));

                if (!fpInstance) {
                    fpInstance = flatpickr(rangoInput, {
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        minDate: 'today',
                        disable: disabled,
                        defaultDate: defaultRange,
                        onReady: (selectedDates, dateStr, instance) => {
                            instance.calendarContainer.classList.add('rounded-xl');
                            if (defaultRange && defaultRange.length === 2) {
                                actualizarResumen(new Date(defaultRange[0]), new Date(defaultRange[1]));
                            }
                        },
                        onChange: (dates) => {
                            if (dates.length === 2) {
                                const [start, end] = dates;
                                fechaEntradaInput.value = start.toISOString().slice(0, 10);
                                fechaSalidaInput.value = end.toISOString().slice(0, 10);
                                actualizarResumen(start, end);
                            } else {
                                fechaEntradaInput.value = '';
                                fechaSalidaInput.value = '';
                                actualizarResumen(null, null);
                            }
                        },
                        onDayCreate: (_, __, ___, dayElem) => {
                            decorateDay(dayElem);
                        },
                    });
                } else {
                    fpInstance.set('disable', disabled);
                    fpInstance.redraw();
                    if (defaultRange) {
                        fpInstance.setDate(defaultRange, true);
                    }
                }

                if (!defaultRange) {
                    actualizarResumen(null, null);
                }
            };

            const cargarDisponibilidad = () => {
                if (!disponibilidadUrl) {
                    inicializarCalendario([]);
                    return;
                }

                fetch(disponibilidadUrl)
                    .then((response) => (response.ok ? response.json() : Promise.reject()))
                    .then((data) => {
                        inicializarCalendario(data.bloques || []);
                    })
                    .catch(() => {
                        inicializarCalendario([]);
                    });
            };

            const abrirModal = (trigger) => {
                const numero = trigger.dataset.habitacionNumero || '';
                const tipo = trigger.dataset.habitacionTipo || 'Habitación';
                const capacidadParse = parseInt(trigger.dataset.habitacionCapacidad || '1', 10);
                const capacidad = Number.isNaN(capacidadParse) ? 1 : capacidadParse;
                nightlyRate = parseFloat(trigger.dataset.habitacionPrecio || '0');
                if (Number.isNaN(nightlyRate)) {
                    nightlyRate = 0;
                }
                disponibilidadUrl = trigger.dataset.disponibilidadUrl || null;
                const estado = (trigger.dataset.habitacionEstado || 'disponible').toLowerCase();
                const imagenUrl = trigger.dataset.habitacionImagen || imagen.src;

                habitacionIdInput.value = trigger.dataset.habitacionId || '';
                const tituloTexto = numero ? `Habitación ${numero}` : tipo;
                titulo.textContent = tituloTexto;
                tipoSpan.textContent = tipo;
                capacidadTexto.textContent = capacidad;
                capacidadMaxTexto.textContent = capacidad;
                tarifaSpan.textContent = nightlyRate.toFixed(2);
                tarifaResumenSpan.textContent = nightlyRate.toFixed(2);
                personasInput.max = capacidad;
                let valorInicial = parseInt(personasInput.value || '1', 10);
                if (Number.isNaN(valorInicial) || valorInicial < 1) {
                    valorInicial = 1;
                }
                if (valorInicial > capacidad) {
                    valorInicial = capacidad;
                }
                personasInput.value = valorInicial;
                imagen.src = imagenUrl;
                imagen.alt = tituloTexto;
                notas.value = '';
                rangoInput.value = '';
                fechaEntradaInput.value = '';
                fechaSalidaInput.value = '';
                actualizarEstadoVisual(estado);
                inicializarCalendario([]);
                cargarDisponibilidad();

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            const cerrarModal = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                if (fpInstance) {
                    fpInstance.clear();
                }
                actualizarResumen(null, null);
            };

            personasInput.addEventListener('input', () => {
                const maxParse = parseInt(personasInput.max || '1', 10);
                const max = Number.isNaN(maxParse) ? 1 : maxParse;
                let value = parseInt(personasInput.value || '1', 10);
                if (Number.isNaN(value) || value < 1) value = 1;
                if (value > max) value = max;
                personasInput.value = value;
            });

            overlay.addEventListener('click', cerrarModal);
            closeButtons.forEach((btn) => btn.addEventListener('click', cerrarModal));

            document.querySelectorAll('[data-modal-open]').forEach((button) => {
                button.addEventListener('click', () => abrirModal(button));
            });

            modal.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    cerrarModal();
                }
            });
        });
    </script>
@endpush
