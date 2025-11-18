@extends('layouts.public')

@section('content')
    <section class="bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-950 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-semibold">Mi perfil</h1>
            <p class="mt-3 text-sm text-white/70 max-w-2xl">Actualiza tus datos personales y mantén tu información de seguridad al día para que podamos brindarte una experiencia impecable en Hotel PASA EL EXTRA Inn.</p>
        </div>
    </section>

    <section class="relative -mt-10 z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-400/30 text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-400/30 text-rose-900">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-indigo-100 text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a9.75 9.75 0 1115 0v.75H4.5v-.75z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Información personal</h2>
                            <p class="text-sm text-slate-500">Esta información nos ayuda a personalizar tu estancia.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('perfil.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-600">Nombre completo</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" required>
                            @error('name')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-600">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" required>
                            @error('email')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-semibold text-slate-600">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" value="{{ old('telefono', $user->telefono) }}" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" required>
                            @error('telefono')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="direccion" class="block text-sm font-semibold text-slate-600">Dirección</label>
                            <textarea id="direccion" name="direccion" rows="2" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" placeholder="Calle, número, ciudad">{{ old('direccion', $user->direccion) }}</textarea>
                            @error('direccion')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Guardar cambios
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-violet-100 text-violet-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Seguridad de tu cuenta</h2>
                            <p class="text-sm text-slate-500">Actualiza tu contraseña regularmente para mantener tu cuenta protegida.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('perfil.change-password') }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-slate-600">Contraseña actual</label>
                            <input type="password" id="current_password" name="current_password" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" required>
                            @error('current_password')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-semibold text-slate-600">Nueva contraseña</label>
                            <input type="password" id="new_password" name="new_password" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" required>
                            @error('new_password')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-semibold text-slate-600">Confirmar nueva contraseña</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="mt-1 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-slate-700" required>
                        </div>

                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-violet-600 text-white font-semibold hover:bg-violet-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            Actualizar contraseña
                        </button>
                    </form>

                    <div class="mt-8 p-5 rounded-2xl bg-slate-50 border border-slate-100 text-sm text-slate-500 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-slate-200 text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.171 3.071-1.171 4.242 0 1.172 1.171 1.172 3.07 0 4.242-.055.055-.111.108-.169.159-.633.563-1.45.909-2.352.909s-1.719-.346-2.352-.91a2.994 2.994 0 01-.169-.158c-1.171-1.171-1.171-3.07 0-4.242z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 15a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75A2.25 2.25 0 0121 11.25v4.5A2.25 2.25 0 0118.75 18H15" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15H5.25A2.25 2.25 0 013 12.75v-1.5A2.25 2.25 0 015.25 9H9" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-semibold text-slate-700">Miembro desde {{ $user->created_at->translatedFormat('F Y') }}</p>
                                <p>Última actualización {{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Tipo de usuario: <span class="text-slate-600">{{ $user->esHuesped() ? 'Huésped' : 'Colaborador' }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
