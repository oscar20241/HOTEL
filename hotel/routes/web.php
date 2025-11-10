<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecepcionistaController;
use App\Http\Controllers\HuespedController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PublicHabitacionController;
use App\Http\Controllers\GuestPortalController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReservacionController;
use Illuminate\Support\Facades\Auth;

// routes/web.php
Route::get('/habitaciones/{habitacion}/disponibilidad', [PublicHabitacionController::class, 'disponibilidad'])
    ->name('habitaciones.disponibilidad');



// Página principal pública con listado de habitaciones
Route::get('/', [PublicHabitacionController::class, 'index'])->name('home');

// Página de detalles de habitación pública
Route::get('/habitaciones/{habitacion}', [PublicHabitacionController::class, 'show'])->name('habitaciones.show');

// Rutas de autenticación (Breeze)
require __DIR__.'/auth.php';

// Tus otras rutas de admin, recepcionista, huésped...
Route::prefix('admin')->middleware(['auth', 'empleado.activo', 'es.admin'])->group(function () {
    Route::get('/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');
});

// Rutas de registro
Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

Route::middleware(['auth'])->group(function () {
    Route::post('/reservaciones', [ReservacionController::class, 'store'])->name('reservaciones.store');
    Route::delete('/reservaciones/{reservacion}', [ReservacionController::class, 'destroy'])->name('reservaciones.destroy');

    Route::post('/reservaciones/{reservacion}/pago/paypal', [PagoController::class, 'storePaypal'])
        ->name('reservaciones.pagar.paypal');

    Route::get('/mi-panel', [GuestPortalController::class, 'index'])->name('huesped.dashboard');

    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->esAdministrador() || $user->esGerente()) {
            return redirect()->route('gerente.dashboard');
        }

        if ($user->esRecepcionista()) {
            return redirect()->route('home');
        }

        return redirect()->route('huesped.dashboard');
    })->name('dashboard');

    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
    Route::put('/perfil/update', [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/change-password', [PerfilController::class, 'changePassword'])->name('perfil.change-password');

    // Ruta de logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

// =============================================
// RUTAS DE ADMINISTRACIÓN DE USUARIOS Y HABITACIONES
// =============================================

Route::middleware(['auth', 'empleado.activo'])->group(function () {
    // Ruta específica para el dashboard del gerente con datos
    Route::get('/gerente/dashboard', [AdminUserController::class, 'index'])->name('gerente.dashboard');
    
    // Rutas de administración de usuarios
    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])->name('admin.usuarios');
    Route::get('/admin/empleados/crear', [AdminUserController::class, 'createEmpleado'])->name('admin.empleados.create');
    Route::post('/admin/empleados', [AdminUserController::class, 'storeEmpleado'])->name('admin.empleados.store');
    Route::get('/admin/empleados/{id}/editar', [AdminUserController::class, 'editEmpleado'])->name('admin.empleados.edit');
    Route::put('/admin/empleados/{id}', [AdminUserController::class, 'updateEmpleado'])->name('admin.empleados.update');
    Route::delete('/admin/usuarios/{id}', [AdminUserController::class, 'destroy'])->name('admin.usuarios.destroy');
    Route::post('/admin/empleados/{id}/cambiar-estado', [AdminUserController::class, 'cambiarEstado'])->name('admin.empleados.cambiar-estado');
    
    // 🆕 RUTAS PARA GESTIÓN DE HABITACIONES (AJAX)
    Route::get('/gerente/habitaciones/{id}', [AdminUserController::class, 'showHabitacion'])->name('gerente.habitaciones.show');
    Route::post('/gerente/habitaciones', [AdminUserController::class, 'storeHabitacion'])->name('gerente.habitaciones.store');
    Route::put('/gerente/habitaciones/{id}', [AdminUserController::class, 'updateHabitacion'])->name('gerente.habitaciones.update');
    Route::delete('/gerente/habitaciones/{id}', [AdminUserController::class, 'destroyHabitacion'])->name('gerente.habitaciones.destroy');

    // 🆕 RUTAS PARA GESTIÓN DE TARIFAS DINÁMICAS
    Route::get('/gerente/tarifas', [AdminUserController::class, 'listTarifas'])->name('gerente.tarifas.index');
    Route::get('/gerente/tarifas/{id}', [AdminUserController::class, 'showTarifa'])->name('gerente.tarifas.show');
    Route::post('/gerente/tarifas', [AdminUserController::class, 'storeTarifa'])->name('gerente.tarifas.store');
    Route::put('/gerente/tarifas/{id}', [AdminUserController::class, 'updateTarifa'])->name('gerente.tarifas.update');
    Route::delete('/gerente/tarifas/{id}', [AdminUserController::class, 'destroyTarifa'])->name('gerente.tarifas.destroy');
});

// Rutas de recepcionista con verificación de estado Y rol
Route::prefix('recepcion')->middleware(['auth', 'empleado.activo', 'es.recepcionista'])->group(function () {
    // ... tus rutas de recepcionista (si las tienes)
});

// Rutas para huéspedes (sin verificación de estado de empleado)
Route::middleware(['auth'])->group(function () {
    // Rutas específicas para huéspedes
});