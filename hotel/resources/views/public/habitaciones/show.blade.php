@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
    $imagenes = $habitacion->imagenes;
    $imagenPrincipal = $imagenes->firstWhere('es_principal', true) ?? $imagenes->first();
    $heroImage = $imagenPrincipal ? Storage::url($imagenPrincipal->ruta_imagen) : 'https://images.unsplash.com/photo-1551776235-dde6d4829808?auto=format&fit=crop&w=1600&q=80';
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
                        <p class="text-3xl font-semibold">${{ number_format($habitacion->precio_actual, 2) }} <span class="text-base font-normal">MXN / noche</span></p>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-sm uppercase tracking-[0.2em]">
                        <span class="h-2.5 w-2.5 rounded-full {{ $habitacion->estado === 'disponible' ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                        {{ ucfirst($habitacion->estado) }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="grid lg:grid-cols-2 gap-10 p-6 sm:p-10">
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button type="button" data-carousel-next class="absolute top-1/2 right-3 -translate-y-1/2 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-lg shadow-slate-900/10 text-slate-600 hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5L15.75 12l-7.5 7.5" />
                            </svg>
                        </button>
                    @endif

                    @if ($imagenes->count() > 1)
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
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h2 class="text-2xl font-semibold text-slate-900">Detalles de la habitación</h2>
                        <p class="text-slate-600 leading-relaxed">Disfruta de un ambiente sofisticado y acogedor diseñado para ofrecer descanso absoluto. Nuestra habitación ofrece acabados premium, ropa de cama hipoalergénica y servicios exclusivos para cada huésped.</p>
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

                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-3">Amenidades destacadas</h3>
                        <div class="flex flex-wrap gap-3">
                            @forelse (collect($habitacion->amenidades ?? []) as $amenidad)
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-700 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    {{ $amenidad }}
                                </span>
                            @empty
                                <p class="text-sm text-slate-500">Pronto añadiremos más información sobre las amenidades disponibles.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-3xl bg-indigo-50 border border-indigo-100 p-6 space-y-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-indigo-600 text-white flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21M9 9.75l3 3 3-3m-3 3V3" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm uppercase tracking-[0.2em] text-indigo-500">Reserva tu estancia</p>
                                <p class="text-lg font-semibold text-indigo-900">Atención personalizada 24/7</p>
                            </div>
                        </div>
                        <p class="text-sm text-indigo-900/80">Nuestro equipo de reservaciones está listo para ayudarte a planear tu visita y diseñar experiencias a la medida. Inicia sesión para confirmar tu reserva.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                Reservar ahora
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                            <a href="tel:+525512345678" class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white text-indigo-600 text-sm font-semibold border border-indigo-100 hover:border-indigo-200 transition">
                                Llamar a recepción
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106a1.125 1.125 0 00-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97a1.125 1.125 0 00.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-slate-900 rounded-3xl overflow-hidden">
            <div class="grid lg:grid-cols-2">
                <div class="p-8 sm:p-12 space-y-4 text-white">
                    <h2 class="text-3xl font-['Playfair_Display'] font-semibold">Servicios pensados para ti</h2>
                    <p class="text-white/70">Acceso al spa, gimnasio 24 horas, servicio a la habitación gourmet y concierge personalizado. Complementa tu estancia con experiencias únicas en la ciudad.</p>
                    <ul class="space-y-3 text-white/80 text-sm">
                        <li class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </span>
                            Traslado al aeropuerto (con costo adicional)
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </span>
                            Experiencias gastronómicas exclusivas
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-carousel]').forEach((carousel) => {
                const slides = carousel.querySelectorAll('[data-carousel-slide]');
                if (!slides.length) {
                    return;
                }

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

                const nextSlide = () => {
                    current = (current + 1) % slides.length;
                    updateSlides();
                };

                const prevSlide = () => {
                    current = (current - 1 + slides.length) % slides.length;
                    updateSlides();
                };

                carousel.querySelector('[data-carousel-next]')?.addEventListener('click', nextSlide);
                carousel.querySelector('[data-carousel-prev]')?.addEventListener('click', prevSlide);

                carousel.querySelectorAll('[data-carousel-thumb]').forEach((thumb, index) => {
                    thumb.addEventListener('click', () => {
                        current = index;
                        updateSlides();
                    });
                });

                updateSlides();
            });
        });
    </script>
@endpush
