@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;

    $placeholderImage = 'https://images.unsplash.com/photo-1551776235-dde6d4829808?auto=format&fit=crop&w=1600&q=80';
    $habitacionesData = $habitaciones->map(function ($habitacion) use ($placeholderImage) {
        return [
            'id' => $habitacion->id,
            'numero' => $habitacion->numero,
            'tipo' => $habitacion->tipoHabitacion->nombre ?? 'Habitación',
            'capacidad' => $habitacion->capacidad,
            'estado' => $habitacion->estado,
            'precio' => number_format($habitacion->precio_actual, 2, '.', ''),
            'disponibilidad' => route('habitaciones.disponibilidad', $habitacion),
            'imagen' => $habitacion->imagenPrincipal
                ? Storage::url($habitacion->imagenPrincipal->ruta_imagen)
                : $placeholderImage,
        ];
    });

    $imagenesReserva = $reservacion->habitacion->imagenes;
    $imagenActiva = $imagenesReserva->first();
@endphp

@section('content')
    <section class="relative bg-slate-900 text-white">
        <div class="absolute inset-0 opacity-40" style="background-image: url('{{ $imagenActiva ? Storage::url($imagenActiva->ruta_imagen) : $placeholderImage }}'); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-slate-900/40"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 space-y-4">
            <p class="uppercase text-sm tracking-[0.3em] text-white/60">Reservación {{ $reservacion->codigo_reserva }}</p>
            <h1 class="text-4xl sm:text-5xl font-['Playfair_Display'] font-semibold">Edita tu estancia</h1>
            <p class="max-w-2xl text-white/75">
                Ajusta fechas, cambia de habitación o actualiza tus notas. Comprobamos la disponibilidad al instante para que solo
                elijas noches libres o sin mantenimiento.
            </p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] items-start">
            <div class="bg-white rounded-3xl shadow-xl p-8 space-y-6">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">Actualizar reservación</h2>
                        <p class="text-sm text-slate-500">Selecciona una nueva habitación o modifica tus fechas.</p>
                    </div>
                    <a href="{{ route('huesped.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                        &larr; Regresar a mi panel
                    </a>
                </div>

                @if ($errors->any())
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm">
                        <p class="font-semibold">Revisa la información proporcionada:</p>
                        <ul class="list-disc list-inside space-y-1 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('reservaciones.update', $reservacion) }}" class="space-y-5" id="form-editar-reserva">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="habitacion_id" class="block text-sm font-semibold text-slate-700">Habitación</label>
                        <select id="habitacion_id" name="habitacion_id" class="mt-1 w-full rounded-xl border border-slate-200 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="" disabled {{ old('habitacion_id', $reservacion->habitacion_id) ? '' : 'selected' }}>Selecciona una habitación</option>
                            @foreach ($habitacionesData as $habitacion)
                                <option value="{{ $habitacion['id'] }}"
                                    data-capacidad="{{ $habitacion['capacidad'] }}"
                                    data-precio="{{ $habitacion['precio'] }}"
                                    data-estado="{{ $habitacion['estado'] }}"
                                    data-availability="{{ $habitacion['disponibilidad'] }}"
                                    data-imagen="{{ $habitacion['imagen'] }}"
                                    data-numero="{{ $habitacion['numero'] }}"
                                    data-tipo="{{ $habitacion['tipo'] }}"
                                    @selected(old('habitacion_id', $reservacion->habitacion_id) == $habitacion['id'])>
                                    {{ $habitacion['numero'] }} · {{ $habitacion['tipo'] }} · Capacidad {{ $habitacion['capacidad'] }} huéspedes
                                </option>
                            @endforeach
                        </select>
                        @error('habitacion_id') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Personas</label>
                            <input type="number" id="numero_huespedes" name="numero_huespedes"
                                min="1" value="{{ old('numero_huespedes', $reservacion->numero_huespedes) }}"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500" required>
                            <small class="text-slate-500">Capacidad máx: <span id="texto-capacidad">{{ $reservacion->habitacion->capacidad }}</span></small>
                            @error('numero_huespedes') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Fechas (entrada / salida)</label>
                            <input type="text" id="rango-fechas" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Selecciona el rango" autocomplete="off" required>
                            <input type="hidden" name="fecha_entrada" id="fecha_entrada" value="{{ old('fecha_entrada', $reservacion->fecha_entrada->toDateString()) }}">
                            <input type="hidden" name="fecha_salida" id="fecha_salida" value="{{ old('fecha_salida', $reservacion->fecha_salida->toDateString()) }}">
                            @error('fecha_entrada') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
                            @error('fecha_salida') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
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
                            <p class="mt-1 text-xl font-semibold text-slate-800"><span id="noches">{{ $reservacion->noches }}</span></p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Tarifa por noche</p>
                            <p class="mt-1 text-xl font-semibold text-slate-800">$<span id="tarifa_noche">{{ number_format(old('habitacion_id') ? optional($habitaciones->firstWhere('id', old('habitacion_id')))->precio_actual ?? $reservacion->habitacion->precio_actual : $reservacion->habitacion->precio_actual, 2) }}</span> MXN</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Total estimado</p>
                            <p class="mt-1 text-xl font-semibold text-slate-800">$<span id="precio_estimado">{{ number_format($reservacion->precio_total, 2) }}</span> MXN</p>
                        </div>
                    </div>

                    <div>
                        <label for="notas" class="block text-sm font-semibold text-slate-700">Notas (opcional)</label>
                        <textarea id="notas" name="notas" rows="3" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Comparte peticiones especiales o detalles adicionales">{{ old('notas', $reservacion->notas) }}</textarea>
                        @error('notas') <div class="text-sm text-rose-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                            Guardar cambios
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-7 7l7-7-7-7" />
                            </svg>
                        </button>
                        <a href="{{ route('huesped.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <aside class="space-y-6">
                <div class="bg-slate-900 text-white rounded-3xl overflow-hidden shadow-xl">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img id="preview-imagen" src="{{ $imagenActiva ? Storage::url($imagenActiva->ruta_imagen) : $placeholderImage }}" alt="Vista previa de la habitación" class="h-full w-full object-cover">
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-white/60">Habitación seleccionada</p>
                            <h3 class="text-2xl font-semibold" id="preview-titulo">Habitación {{ $reservacion->habitacion->numero }}</h3>
                            <p class="text-sm text-white/70" id="preview-tipo">{{ $reservacion->habitacion->tipoHabitacion->nombre ?? 'Habitación' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="bg-white/10 rounded-2xl p-4">
                                <p class="text-white/60 uppercase tracking-[0.25em] text-xs">Capacidad</p>
                                <p class="text-white font-semibold" id="preview-capacidad">{{ $reservacion->habitacion->capacidad }} huéspedes</p>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4">
                                <p class="text-white/60 uppercase tracking-[0.25em] text-xs">Estado</p>
                                <p class="text-white font-semibold capitalize" id="preview-estado">{{ $reservacion->habitacion->estado }}</p>
                            </div>
                        </div>
                        <div class="bg-white/10 rounded-2xl p-4 text-sm">
                            <p class="text-white/60 uppercase tracking-[0.25em] text-xs">Notas actuales</p>
                            <p class="text-white/80">{{ $reservacion->notas ? $reservacion->notas : 'Sin notas registradas.' }}</p>
                        </div>
                    </div>
                </div>

                @if ($imagenesReserva->count() > 1)
                    <div class="bg-white rounded-3xl shadow-lg p-5">
                        <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-[0.3em] mb-3">Galería actual</h4>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach ($imagenesReserva as $imagen)
                                <img src="{{ Storage::url($imagen->ruta_imagen) }}" alt="Imagen de la habitación" class="h-20 w-full object-cover rounded-2xl">
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-day.is-disponible {
            background-color: rgba(16, 185, 129, 0.12);
            color: #0f172a;
        }

        .flatpickr-day.is-disponible:hover,
        .flatpickr-day.is-disponible:focus {
            background-color: rgba(16, 185, 129, 0.28);
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectHabitacion = document.getElementById('habitacion_id');
            const inpPersonas = document.getElementById('numero_huespedes');
            const textoCapacidad = document.getElementById('texto-capacidad');
            const tarifaNocheSpan = document.getElementById('tarifa_noche');
            const nochesSpan = document.getElementById('noches');
            const totalSpan = document.getElementById('precio_estimado');
            const fechaEntradaInput = document.getElementById('fecha_entrada');
            const fechaSalidaInput = document.getElementById('fecha_salida');
            const previewImagen = document.getElementById('preview-imagen');
            const previewTitulo = document.getElementById('preview-titulo');
            const previewTipo = document.getElementById('preview-tipo');
            const previewCapacidad = document.getElementById('preview-capacidad');
            const previewEstado = document.getElementById('preview-estado');

            const disponibilidadActual = { bloques: [] };
            let fpInstance = null;
            let nightlyRate = parseFloat(selectHabitacion.selectedOptions[0]?.dataset.precio || '{{ number_format($reservacion->habitacion->precio_actual, 2, '.', '') }}');

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
                disponibilidadActual.bloques = bloques || [];
                const disabled = disponibilidadActual.bloques.map((b) => ({ from: b.from, to: b.to }));

                if (!fpInstance) {
                    fpInstance = flatpickr('#rango-fechas', {
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        minDate: 'today',
                        disable: disabled,
                        defaultDate: defaultRange,
                        onReady: (selectedDates, dateStr, instance) => {
                            if (defaultRange && defaultRange.length === 2) {
                                updateResumen(new Date(defaultRange[0]), new Date(defaultRange[1]));
                            }
                            instance.calendarContainer.classList.add('rounded-xl');
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
                        onDayCreate: function (_, __, ___, dayElem) {
                            decorateDay(dayElem);
                        }
                    });
                } else {
                    fpInstance.set('disable', disabled);
                    fpInstance.redraw();
                    const selectedDates = fpInstance.selectedDates;
                    if (selectedDates.length === 2) {
                        updateResumen(selectedDates[0], selectedDates[1]);
                    } else {
                        updateResumen(null, null);
                    }
                }
            };

            const cargarDisponibilidad = (option) => {
                if (!option) {
                    inicializarCalendario([]);
                    return;
                }

                const url = `${option.dataset.availability}?exclude_reservacion={{ $reservacion->id }}`;

                fetch(url)
                    .then((response) => response.ok ? response.json() : Promise.reject())
                    .then((data) => {
                        inicializarCalendario(data.bloques || [], [fechaEntradaInput.value, fechaSalidaInput.value]);
                    })
                    .catch(() => {
                        inicializarCalendario([], [fechaEntradaInput.value, fechaSalidaInput.value]);
                    });
            };

            const actualizarPreview = (option) => {
                if (!option) {
                    return;
                }

                const capacidad = option.dataset.capacidad;
                const estado = option.dataset.estado;
                const imagen = option.dataset.imagen;
                const tipo = option.dataset.tipo;
                const numero = option.dataset.numero;

                previewImagen.src = imagen;
                previewTitulo.textContent = numero ? `Habitación ${numero}` : option.textContent.split('·')[0].trim();
                previewTipo.textContent = tipo || previewTipo.textContent;
                previewCapacidad.textContent = `${capacidad} huéspedes`;
                previewEstado.textContent = estado;

                inpPersonas.max = capacidad;
                textoCapacidad.textContent = capacidad;
                if (parseInt(inpPersonas.value, 10) > parseInt(capacidad, 10)) {
                    inpPersonas.value = capacidad;
                }
            };

            const actualizarTarifa = (option) => {
                if (!option) {
                    return;
                }

                nightlyRate = parseFloat(option.dataset.precio || nightlyRate);
                tarifaNocheSpan.textContent = parseFloat(nightlyRate).toFixed(2);

                const selectedDates = fpInstance ? fpInstance.selectedDates : [];
                if (selectedDates && selectedDates.length === 2) {
                    updateResumen(selectedDates[0], selectedDates[1]);
                }
            };

            const opcionInicial = selectHabitacion.selectedOptions[0];
            actualizarPreview(opcionInicial);
            inicializarCalendario([], [fechaEntradaInput.value, fechaSalidaInput.value]);
            cargarDisponibilidad(opcionInicial);
            actualizarTarifa(opcionInicial);

            selectHabitacion.addEventListener('change', () => {
                const option = selectHabitacion.selectedOptions[0];
                actualizarPreview(option);
                actualizarTarifa(option);
                cargarDisponibilidad(option);
            });
        });
    </script>
@endpush
