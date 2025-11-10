@extends('layouts.public')

@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <section class="bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-12 items-start">
                <div class="space-y-6">
                    <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-white/10 text-xs font-semibold uppercase tracking-[0.3em]">
                        {{ now()->translatedFormat('d \d\e F \d\e Y') }}
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-['Playfair_Display'] leading-tight">
                        Bienvenido de nuevo, {{ Auth::user()->name }}
                    </h1>
                    <p class="text-base sm:text-lg text-slate-200/90 max-w-2xl">
                        Planea tu próxima escapada, revisa tus reservaciones y mantén tus datos actualizados desde esta nueva experiencia pensada para huéspedes. Todo lo que necesitas para disfrutar de Hotel PASA EL EXTRA Inn está aquí.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="#reservar" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white text-indigo-600 font-semibold shadow-lg shadow-black/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nueva reservación
                        </a>
                        <a href="#mis-reservas" class="inline-flex items-center gap-2 px-5 py-3 rounded-full border border-white/40 text-white font-semibold hover:bg-white/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m6.75-2.25v12A2.25 2.25 0 0119.5 21H4.5A2.25 2.25 0 012.25 18V6A2.25 2.25 0 014.5 3.75h15A2.25 2.25 0 0121.75 6z" />
                            </svg>
                            Mis reservaciones
                        </a>
                        <a href="{{ route('perfil') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full border border-white/40 text-white font-semibold hover:bg-white/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a9.75 9.75 0 1115 0v.75H4.5v-.75z" />
                            </svg>
                            Mi perfil
                        </a>
                    </div>
                </div>
                <div class="bg-white/10 rounded-3xl border border-white/10 backdrop-blur p-8 shadow-2xl shadow-black/30 space-y-6">
                    <h2 class="text-lg font-semibold tracking-wide uppercase text-white/80">Tu próxima estancia</h2>
                    @if ($proximaReservacion)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-white/60">Código</span>
                                <span class="text-sm font-semibold">{{ $proximaReservacion->codigo_reserva }}</span>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-white/60">Habitación</p>
                                    <p class="text-lg font-semibold">{{ $proximaReservacion->habitacion->numero }} · {{ $proximaReservacion->habitacion->tipoHabitacion->nombre }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3 rounded-xl bg-white/5">
                                        <p class="text-xs uppercase tracking-widest text-white/50">Check-in</p>
                                        <p class="text-sm font-semibold">{{ $proximaReservacion->fecha_entrada->translatedFormat('d M Y') }}</p>
                                    </div>
                                    <div class="p-3 rounded-xl bg-white/5">
                                        <p class="text-xs uppercase tracking-widest text-white/50">Check-out</p>
                                        <p class="text-sm font-semibold">{{ $proximaReservacion->fecha_salida->translatedFormat('d M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-white/60">Huéspedes</span>
                                    <span class="font-semibold">{{ $proximaReservacion->numero_huespedes }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-white/60">Total estimado</span>
                                    <span class="font-semibold">${{ number_format($proximaReservacion->precio_total, 2) }} MXN</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-sm text-white/70 leading-relaxed">
                            <p class="font-semibold text-base">Aún no tienes una próxima reservación.</p>
                            <p>Explora las habitaciones disponibles y agenda tu próxima visita en pocos pasos.</p>
                        </div>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="mt-10 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-400/30 text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-10 p-4 rounded-2xl bg-rose-500/10 border border-rose-400/30 text-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-10 p-4 rounded-2xl bg-rose-500/10 border border-rose-400/30 text-rose-100">
                    <p class="font-semibold mb-2">No pudimos completar tu solicitud:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    <section id="reservar" class="relative -mt-12 z-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-10 items-stretch">
                <div class="p-8 rounded-3xl bg-white shadow-xl">
                    <h2 class="text-2xl font-semibold text-slate-800">Generar una nueva reservación</h2>
                    <p class="mt-2 text-sm text-slate-500">Selecciona fechas y la habitación ideal para tu estancia. Nuestro equipo confirmará la disponibilidad.</p>
                    @php
                        $habitacionSeleccionada = $habitaciones->firstWhere('id', old('habitacion_id')) ?? $habitaciones->first();
                        $capacidadInicial = $habitacionSeleccionada?->capacidad;
                        $tarifaInicial = $habitacionSeleccionada ? number_format($habitacionSeleccionada->precio_actual, 2, '.', '') : '0.00';
                        $nochesIniciales = 0;
                        $estimadoInicial = 0.00;

                        if (old('fecha_entrada') && old('fecha_salida') && $habitacionSeleccionada) {
                            try {
                                $entradaAnterior = Carbon::parse(old('fecha_entrada'));
                                $salidaAnterior = Carbon::parse(old('fecha_salida'));
                                $nochesIniciales = max(0, $entradaAnterior->diffInDays($salidaAnterior));
                                $estimadoInicial = $nochesIniciales * (float) $habitacionSeleccionada->precio_actual;
                            } catch (\Throwable $e) {
                                $nochesIniciales = 0;
                                $estimadoInicial = 0.00;
                            }
                        }
                    @endphp
                    <form method="POST" action="{{ route('reservaciones.store') }}" class="mt-6 space-y-6" id="form-nueva-reserva">
                        @csrf
                        <div>
                            <label for="habitacion_id" class="block text-sm font-semibold text-slate-600">Habitación</label>
                            <select id="habitacion_id" name="habitacion_id" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" required>
                                <option value="" disabled>Selecciona una habitación</option>
                                @foreach ($habitaciones as $habitacion)
                                    <option value="{{ $habitacion->id }}"
                                        data-capacidad="{{ $habitacion->capacidad }}"
                                        data-precio="{{ number_format($habitacion->precio_actual, 2, '.', '') }}"
                                        data-availability="{{ route('habitaciones.disponibilidad', $habitacion) }}"
                                        @selected(old('habitacion_id', $habitacionSeleccionada?->id) == $habitacion->id)>
                                        {{ $habitacion->numero }} · {{ $habitacion->tipoHabitacion->nombre }} · Capacidad {{ $habitacion->capacidad }} huéspedes
                                    </option>
                                @endforeach
                            </select>
                            @error('habitacion_id')
                                <div class="text-sm text-rose-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div>
                                    <label for="numero_huespedes" class="block text-sm font-semibold text-slate-600">Número de huéspedes</label>
                                    <input type="number" min="1" id="numero_huespedes" name="numero_huespedes" value="{{ old('numero_huespedes', 1) }}" max="{{ $capacidadInicial ?? '' }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" required>
                                    <small class="text-slate-500">Capacidad máx: <span id="texto-capacidad">{{ $capacidadInicial ?? '—' }}</span></small>
                                    @error('numero_huespedes')
                                        <div class="text-sm text-rose-600 mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="rounded-xl border border-indigo-100 bg-indigo-50/80 p-4">
                                    <p class="text-xs text-indigo-800 uppercase tracking-wide font-semibold">Tip</p>
                                    <p class="text-sm text-indigo-900/80">Los precios se calculan con la tarifa vigente de cada categoría. Recibirás la confirmación por correo.</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-600 mb-1">Fechas (entrada / salida)</label>
                                <input type="text" id="rango-fechas" class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700 px-3 py-2" placeholder="Selecciona el rango de fechas" autocomplete="off" required>
                                <input type="hidden" name="fecha_entrada" id="fecha_entrada" value="{{ old('fecha_entrada') }}">
                                <input type="hidden" name="fecha_salida" id="fecha_salida" value="{{ old('fecha_salida') }}">
                                @error('fecha_entrada')
                                    <div class="text-sm text-rose-600 mt-1">{{ $message }}</div>
                                @enderror
                                @error('fecha_salida')
                                    <div class="text-sm text-rose-600 mt-1">{{ $message }}</div>
                                @enderror
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500/70"></span> Disponible</span>
                                    <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-amber-500/80"></span> Mantenimiento</span>
                                    <span class="inline-flex items-center gap-1"><span class="h-2.5 w-2.5 rounded-full bg-rose-500/80"></span> Ocupada</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Noches</p>
                                <p class="mt-1 text-xl font-semibold text-slate-800"><span id="noches">{{ $nochesIniciales }}</span></p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Tarifa por noche</p>
                                <p class="mt-1 text-xl font-semibold text-slate-800">$<span id="tarifa_noche">{{ $tarifaInicial }}</span> MXN</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Estimado total</p>
                                <p class="mt-1 text-xl font-semibold text-slate-800">$<span id="precio_estimado">{{ number_format($estimadoInicial, 2, '.', '') }}</span> MXN</p>
                            </div>
                        </div>
                        <div>
                            <label for="notas" class="block text-sm font-semibold text-slate-600">Notas adicionales</label>
                            <textarea id="notas" name="notas" rows="3" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" placeholder="Comparte preferencias, horarios de llegada o necesidades especiales">{{ old('notas') }}</textarea>
                            @error('notas')
                                <div class="text-sm text-rose-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-3 rounded-full bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                            Solicitar reservación
                        </button>
                    </form>
                </div>
                <div class="rounded-3xl overflow-hidden shadow-xl">
                    <div class="h-full bg-gradient-to-br from-indigo-900 via-slate-900 to-black">
                        <div class="h-full w-full bg-[radial-gradient(circle_at_top,_rgba(99,102,241,0.45),transparent_55%)] p-8 flex flex-col justify-end text-white">
                            <h3 class="text-2xl font-semibold">Experiencias PASA EL EXTRA Inn</h3>
                            <p class="mt-3 text-sm text-white/80 max-w-sm">Disfruta del spa nocturno, cenas de autor y nuestro bar celestial con vista a la ciudad. Personaliza tu estancia en el formulario y lo haremos realidad.</p>
                            <p class="mt-6 text-xs uppercase tracking-[0.3em] text-white/60">Servicio disponible 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="mis-reservas" class="mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h2 class="text-3xl font-['Playfair_Display'] text-slate-900">Mis reservaciones</h2>
                    <p class="text-sm text-slate-500 mt-1">Consulta el historial y estado de tus estancias con nosotros.</p>
                </div>
                <a href="#reservar" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold hover:bg-indigo-200 transition">
                    Crear nueva
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </a>
            </div>

            <div class="mt-8 bg-white shadow-lg shadow-slate-200/60 rounded-3xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th class="px-6 py-4">Reserva</th>
                                <th class="px-6 py-4">Habitación</th>
                                <th class="px-6 py-4">Fechas</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($reservaciones as $reservacion)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">{{ $reservacion->codigo_reserva }}</p>
                                        <p class="text-xs text-slate-500">Registrada el {{ $reservacion->created_at->translatedFormat('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-12 w-12 rounded-2xl bg-slate-100 overflow-hidden">
                                                @if ($reservacion->habitacion->imagenPrincipal)
                                                    <img src="{{ Storage::url($reservacion->habitacion->imagenPrincipal->ruta_imagen) }}" alt="Habitación {{ $reservacion->habitacion->numero }}" class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full bg-gradient-to-br from-indigo-200 to-indigo-400"></div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $reservacion->habitacion->numero }}</p>
                                                <p class="text-xs text-slate-500">{{ $reservacion->habitacion->tipoHabitacion->nombre }} · {{ $reservacion->numero_huespedes }} huéspedes</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">{{ $reservacion->fecha_entrada->translatedFormat('d M') }} – {{ $reservacion->fecha_salida->translatedFormat('d M Y') }}</p>
                                        <p class="text-xs text-slate-500">{{ $reservacion->fecha_entrada->diffInDays($reservacion->fecha_salida) }} noches</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">${{ number_format($reservacion->precio_total, 2) }}</p>
                                        @if ($reservacion->saldo_pendiente > 0)
                                            <p class="text-xs text-rose-600 mt-1">Saldo pendiente: ${{ number_format($reservacion->saldo_pendiente, 2) }} MXN</p>
                                        @else
                                            <p class="text-xs text-emerald-600 mt-1">Pagado</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $estados = [
                                                'pendiente' => 'bg-amber-100 text-amber-700',
                                                'confirmada' => 'bg-emerald-100 text-emerald-700',
                                                'activa' => 'bg-sky-100 text-sky-700',
                                                'completada' => 'bg-slate-200 text-slate-700',
                                                'cancelada' => 'bg-rose-100 text-rose-700',
                                            ];
                                        @endphp
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold uppercase {{ $estados[$reservacion->estado] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ ucfirst($reservacion->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('habitaciones.show', $reservacion->habitacion) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                                                Ver habitación
                                            </a>
                                            @if ($reservacion->estado === 'confirmada' || $reservacion->estado === 'activa' || ($reservacion->estado === 'pendiente' && $reservacion->total_pagado > 0))
                                                <a href="{{ route('reservaciones.edit', $reservacion) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-800">
                                                    Editar
                                                </a>
                                            @endif
                                            @if ($reservacion->estado === 'pendiente')
                                                <button type="button"
                                                    class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:text-emerald-700"
                                                    data-paypal-trigger
                                                    data-reservacion-id="{{ $reservacion->id }}"
                                                    data-monto="{{ number_format($reservacion->saldo_pendiente, 2, '.', '') }}"
                                                    data-paypal-url="{{ route('reservaciones.pago.paypal', $reservacion) }}">
                                                    Pagar con PayPal
                                                </button>
                                                @if ($reservacion->total_pagado > 0 && $reservacion->saldo_pendiente > 0)
                                                    <span class="text-[11px] text-emerald-600">Saldo adicional por ajustes recientes.</span>
                                                @endif
                                            @endif
                                            @if ($reservacion->puedeCancelarse())
                                                <form method="POST" action="{{ route('reservaciones.destroy', $reservacion) }}" onsubmit="return confirm('¿Cancelar la reservación {{ $reservacion->codigo_reserva }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-700">
                                                        Cancelar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Aún no registras reservaciones. Cuando lo hagas, aparecerán aquí.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-20 bg-slate-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-['Playfair_Display']">Descubre más habitaciones</h2>
                    <p class="text-sm text-white/70 mt-2">Personaliza tu estancia con nuestras categorías disponibles actualmente.</p>
                </div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-200 hover:text-white transition">
                    Ver todas las habitaciones
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div class="mt-10 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($habitaciones->take(6) as $habitacion)
                    <article class="group bg-white/5 border border-white/10 rounded-3xl overflow-hidden backdrop-blur shadow-xl shadow-black/40">
                        <div class="aspect-[4/3] overflow-hidden">
                            @if ($habitacion->imagenPrincipal)
                                <img src="{{ Storage::url($habitacion->imagenPrincipal->ruta_imagen) }}" alt="Habitación {{ $habitacion->numero }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-indigo-400/60 to-purple-500/60"></div>
                            @endif
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold">Habitación {{ $habitacion->numero }}</h3>
                                <span class="inline-flex items-center gap-1 text-sm text-indigo-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                    </svg>
                                    {{ $habitacion->capacidad }} huéspedes
                                </span>
                            </div>
                            <p class="text-sm text-white/70">{{ $habitacion->tipoHabitacion->nombre }}</p>
                            <p class="text-2xl font-semibold text-white">${{ number_format($habitacion->precio_actual, 2) }} <span class="text-sm text-white/60">/ noche</span></p>
                            <div class="flex items-center justify-between">
                                <a href="{{ route('habitaciones.show', $habitacion) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-200 group-hover:text-white transition">
                                    Ver detalles
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                                <a href="#reservar" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-sm font-semibold text-white hover:bg-white/20 transition">
                                    Reservar
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <div id="paypal-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>
        <div class="relative h-full w-full flex items-center justify-center px-4">
            <div class="w-full max-w-md rounded-3xl bg-white shadow-2xl shadow-slate-900/40 p-6 space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Completar pago con PayPal</h3>
                        <p class="text-sm text-slate-500">Utiliza nuestro entorno sandbox para simular el pago de tu reservación.</p>
                    </div>
                    <button type="button" id="paypal-modal-close" class="text-slate-400 hover:text-slate-600">
                        <span class="sr-only">Cerrar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="paypal-message" class="text-sm text-emerald-600 hidden"></div>
                <div id="paypal-error" class="text-sm text-rose-600 hidden"></div>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 text-sm text-slate-600">
                        <input type="checkbox" id="paypal-remember-session"
                            class="mt-0.5 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>
                            Guardar mi sesión de PayPal en este dispositivo
                            <span class="block text-xs text-slate-400">Solo se recordará para este navegador.</span>
                        </span>
                    </label>
                    <button type="button" id="paypal-forget-session"
                        class="text-xs font-semibold text-rose-500 hover:text-rose-600 hidden">
                        Olvidar cuenta guardada
                    </button>
                </div>
                <div id="paypal-button-container" class="mt-2"></div>
                <p class="text-xs text-slate-400">Este flujo utiliza credenciales de prueba (sandbox). No se realizan cargos reales.</p>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-day.is-disponible {
            background-color: rgba(14, 165, 233, 0.12);
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
            const selectHabitacion = document.getElementById('habitacion_id');
            const rangoFechas = document.getElementById('rango-fechas');
            const inpPersonas = document.getElementById('numero_huespedes');
            const textoCapacidad = document.getElementById('texto-capacidad');
            const tarifaNocheSpan = document.getElementById('tarifa_noche');
            const nochesSpan = document.getElementById('noches');
            const totalSpan = document.getElementById('precio_estimado');
            const fechaEntradaInput = document.getElementById('fecha_entrada');
            const fechaSalidaInput = document.getElementById('fecha_salida');

            if (!selectHabitacion || !rangoFechas || !window.flatpickr) {
                return;
            }

            if (window.flatpickr.l10ns?.es) {
                window.flatpickr.localize(window.flatpickr.l10ns.es);
            }

            const disponibilidadActual = { bloques: [] };
            let fpInstance = null;
            let nightlyRate = parseFloat(selectHabitacion.selectedOptions[0]?.dataset.precio || tarifaNocheSpan?.textContent || '0');

            const clampHuespedes = () => {
                if (!inpPersonas) {
                    return;
                }
                const capacidad = parseInt(textoCapacidad?.textContent || inpPersonas.max || '1', 10) || 1;
                let value = parseInt(inpPersonas.value || '1', 10);
                if (Number.isNaN(value) || value < 1) {
                    value = 1;
                }
                if (value > capacidad) {
                    value = capacidad;
                }
                inpPersonas.value = value;
            };

            const updateResumen = (startDate, endDate) => {
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

                if (bloque) {
                    dayElem.classList.add(`is-${bloque.estado}`);
                    const estadoTexto = bloque.estado === 'ocupada' ? 'Ocupada' : 'Mantenimiento';
                    dayElem.setAttribute('aria-label', `${dayElem.getAttribute('aria-label')} – ${estadoTexto}`);
                } else {
                    dayElem.classList.add('is-disponible');
                }
            };

            const inicializarCalendario = (bloques, defaultRange = null) => {
                disponibilidadActual.bloques = bloques || [];
                const disabled = disponibilidadActual.bloques.map((b) => ({ from: b.from, to: b.to }));

                if (!fpInstance) {
                    fpInstance = flatpickr(rangoFechas, {
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        minDate: 'today',
                        disable: disabled,
                        defaultDate: defaultRange,
                        onReady: (selectedDates, _, instance) => {
                            instance.calendarContainer.classList.add('rounded-xl');
                            if (selectedDates.length === 2) {
                                updateResumen(selectedDates[0], selectedDates[1]);
                            }
                        },
                        onChange: (dates) => {
                            if (dates.length === 2) {
                                const [start, end] = dates;
                                fechaEntradaInput.value = start.toISOString().slice(0, 10);
                                fechaSalidaInput.value = end.toISOString().slice(0, 10);
                                updateResumen(start, end);
                            } else {
                                fechaEntradaInput.value = '';
                                fechaSalidaInput.value = '';
                                updateResumen(null, null);
                            }
                        },
                        onDayCreate: (_, __, ___, dayElem) => decorateDay(dayElem),
                    });
                } else {
                    fpInstance.set('disable', disabled);
                    fpInstance.redraw();
                    const selectedDates = fpInstance.selectedDates;
                    if (selectedDates.length === 2) {
                        updateResumen(selectedDates[0], selectedDates[1]);
                    }
                }
            };

            const cargarDisponibilidad = (option, defaultRange = null) => {
                if (!option) {
                    inicializarCalendario([], defaultRange);
                    return;
                }

                const url = option.dataset.availability;
                fetch(url)
                    .then((response) => (response.ok ? response.json() : Promise.reject()))
                    .then((data) => {
                        inicializarCalendario(data.bloques || [], defaultRange);
                    })
                    .catch(() => {
                        inicializarCalendario([], defaultRange);
                    });
            };

            const actualizarHabitacion = (option) => {
                if (!option) {
                    return;
                }

                nightlyRate = parseFloat(option.dataset.precio || nightlyRate || '0');
                if (tarifaNocheSpan) {
                    tarifaNocheSpan.textContent = nightlyRate.toFixed(2);
                }

                if (inpPersonas) {
                    const capacidad = option.dataset.capacidad || inpPersonas.max || '1';
                    inpPersonas.max = capacidad;
                    if (textoCapacidad) {
                        textoCapacidad.textContent = capacidad;
                    }
                    clampHuespedes();
                }
            };

            const defaultRange = (fechaEntradaInput.value && fechaSalidaInput.value)
                ? [fechaEntradaInput.value, fechaSalidaInput.value]
                : null;

            const opcionInicial = selectHabitacion.selectedOptions[0];
            actualizarHabitacion(opcionInicial);
            inicializarCalendario([], defaultRange);
            cargarDisponibilidad(opcionInicial, defaultRange);

            selectHabitacion.addEventListener('change', () => {
                const option = selectHabitacion.selectedOptions[0];
                actualizarHabitacion(option);
                cargarDisponibilidad(option);
            });

            inpPersonas?.addEventListener('input', clampHuespedes);
        });
    </script>
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=MXN&intent=capture"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('paypal-modal');
            const closeModal = document.getElementById('paypal-modal-close');
            const buttonContainer = document.getElementById('paypal-button-container');
            const successMessage = document.getElementById('paypal-message');
            const errorMessage = document.getElementById('paypal-error');
            let currentReservation = null;
            let currentPaypalUrl = null;
            let currentAmount = null;
            const rememberCheckbox = document.getElementById('paypal-remember-session');
            const forgetButton = document.getElementById('paypal-forget-session');
            const rememberKey = 'hotel-paypal-remember-session';

            const supportsLocalStorage = (() => {
                try {
                    const probeKey = '__paypal_probe__';
                    window.localStorage.setItem(probeKey, '1');
                    window.localStorage.removeItem(probeKey);
                    return true;
                } catch (error) {
                    return false;
                }
            })();

            const isRemembering = () => {
                if (!supportsLocalStorage) {
                    return false;
                }
                return window.localStorage.getItem(rememberKey) === '1';
            };

            const syncRememberControls = () => {
                const remembered = isRemembering();
                if (rememberCheckbox) {
                    rememberCheckbox.checked = remembered;
                    rememberCheckbox.disabled = !supportsLocalStorage;
                }
                if (forgetButton) {
                    forgetButton.classList.toggle('hidden', !remembered);
                    forgetButton.disabled = !supportsLocalStorage;
                }
            };

            const setRemembering = (value) => {
                if (!supportsLocalStorage) {
                    return;
                }
                if (value) {
                    window.localStorage.setItem(rememberKey, '1');
                } else {
                    window.localStorage.removeItem(rememberKey);
                }
                syncRememberControls();
            };

            syncRememberControls();

            const flushPaypalSession = () => {
                return new Promise(resolve => {
                    let settled = false;
                    const finalize = () => {
                        if (!settled) {
                            settled = true;
                            resolve();
                        }
                    };

                    const iframe = document.createElement('iframe');
                    iframe.src = 'https://www.paypal.com/signout';
                    iframe.title = 'paypal-signout';
                    iframe.tabIndex = -1;
                    iframe.setAttribute('aria-hidden', 'true');
                    iframe.style.position = 'absolute';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = '0';
                    iframe.onload = () => {
                        finalize();
                        setTimeout(() => iframe.remove(), 0);
                    };
                    iframe.onerror = () => {
                        iframe.remove();
                        finalize();
                    };
                    document.body.appendChild(iframe);
                    setTimeout(() => {
                        if (document.body.contains(iframe)) {
                            iframe.remove();
                        }
                        finalize();
                    }, 4000);
                });
            };

            const hideModal = () => {
                modal.classList.add('hidden');
                buttonContainer.innerHTML = '';
                successMessage.classList.add('hidden');
                errorMessage.classList.add('hidden');
                currentReservation = null;
                currentPaypalUrl = null;
            };

            const renderPaypalButtons = (paypalUrl, amount) => {
                buttonContainer.innerHTML = '';
                successMessage.classList.add('hidden');
                errorMessage.classList.add('hidden');

                paypal.Buttons({
                    style: {
                        color: 'gold',
                        shape: 'pill',
                        label: 'pay',
                        layout: 'vertical'
                    },
                    createOrder: (data, actions) => {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: amount,
                                    currency_code: 'MXN'
                                }
                            }]
                        });
                    },
                    onApprove: (data, actions) => {
                        return actions.order.capture().then(() => {
                            return fetch(paypalUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ paypal_order_id: data.orderID })
                            })
                                .then(async response => {
                                    if (!response.ok) {
                                        const payload = await response.json().catch(() => null);
                                        const message = payload?.message || 'No se pudo registrar el pago.';
                                        throw new Error(message);
                                    }
                                    return response.json();
                                })
                                .then(() => {
                                    successMessage.textContent = '¡Pago completado! Actualizaremos tu reservación.';
                                    successMessage.classList.remove('hidden');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                })
                                .catch(error => {
                                    errorMessage.textContent = error.message || 'Ocurrió un problema al registrar el pago. Intenta nuevamente.';
                                    errorMessage.classList.remove('hidden');
                                });
                        });
                    },
                    onError: () => {
                        errorMessage.textContent = 'No fue posible iniciar el proceso con PayPal en este momento.';
                        errorMessage.classList.remove('hidden');
                    }
                }).render('#paypal-button-container');
            };

            document.querySelectorAll('[data-paypal-trigger]').forEach(button => {
                button.addEventListener('click', async () => {
                    currentReservation = button.dataset.reservacionId;
                    currentPaypalUrl = button.dataset.paypalUrl;
                    currentAmount = button.dataset.monto;
                    modal.classList.remove('hidden');
                    syncRememberControls();
                    if (isRemembering()) {
                        buttonContainer.innerHTML = '<p class="text-sm text-slate-500">Recuperando tu sesión guardada…</p>';
                    } else {
                        buttonContainer.innerHTML = '<p class="text-sm text-slate-500">Preparando PayPal…</p>';
                        await flushPaypalSession();
                    }
                    renderPaypalButtons(currentPaypalUrl, currentAmount);
                });
            });

            closeModal.addEventListener('click', hideModal);

            rememberCheckbox?.addEventListener('change', async () => {
                if (rememberCheckbox.checked) {
                    setRemembering(true);
                    successMessage.textContent = 'Recordaremos tu sesión de PayPal en este dispositivo.';
                    successMessage.classList.remove('hidden');
                    errorMessage.classList.add('hidden');
                } else {
                    setRemembering(false);
                    await flushPaypalSession();
                    successMessage.classList.add('hidden');
                    errorMessage.classList.add('hidden');
                    if (currentPaypalUrl && currentAmount) {
                        buttonContainer.innerHTML = '<p class="text-sm text-slate-500">Preparando PayPal…</p>';
                        renderPaypalButtons(currentPaypalUrl, currentAmount);
                    }
                }
            });

            forgetButton?.addEventListener('click', async () => {
                setRemembering(false);
                if (rememberCheckbox) {
                    rememberCheckbox.checked = false;
                }
                await flushPaypalSession();
                const announceCleared = () => {
                    successMessage.textContent = 'Hemos olvidado la cuenta guardada de PayPal.';
                    successMessage.classList.remove('hidden');
                    errorMessage.classList.add('hidden');
                };
                if (currentPaypalUrl && currentAmount) {
                    buttonContainer.innerHTML = '<p class="text-sm text-slate-500">Preparando PayPal…</p>';
                    renderPaypalButtons(currentPaypalUrl, currentAmount);
                    announceCleared();
                } else {
                    buttonContainer.innerHTML = '';
                    announceCleared();
                }
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    hideModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    hideModal();
                }
            });
        });
    </script>
@endpush
