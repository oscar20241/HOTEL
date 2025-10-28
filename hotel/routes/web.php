<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecepcionistaController;
use App\Http\Controllers\HuespedController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Auth;

// Ruta PRINCIPAL - funciona para ambos: login y dashboard
Route::get('/', function () {
    // Si está autenticado, mostrar dashboard según rol
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->esAdministrador() || $user->esGerente()) {
            // En lugar de cargar la vista directamente, redirige al controlador
            return app(AdminUserController::class)->index();
        } elseif ($user->esRecepcionista()) {
            return view('Recepcionista');
        } else {
            return view('Huesped');
        }
    }
    
    // Si no está autenticado, mostrar login
    return view('login');
})->name('home');

Route::get('/registro', function () {
    return view('Registro');
})->name('registro');

// Rutas de autenticación (Breeze)
require __DIR__.'/auth.php';

// La ruta /dashboard puede redirigir a /
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Tus otras rutas de admin, recepcionista, huésped...
Route::prefix('admin')->middleware(['auth', 'empleado.activo', 'es.admin'])->group(function () {
    Route::get('/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');
});

// Rutas de registro
Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
    Route::put('/perfil/update', [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/change-password', [PerfilController::class, 'changePassword'])->name('perfil.change-password');
    
    // Ruta de logout
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

// Rutas de administración de usuarios - AHORA CON VERIFICACIÓN DE ESTADO
Route::middleware(['auth', 'empleado.activo'])->group(function () {
    // Ruta específica para el dashboard del gerente con datos
    Route::get('/gerente/dashboard', [AdminUserController::class, 'index'])->name('gerente.dashboard');
    
    // Rutas de administración
    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])->name('admin.usuarios');
    Route::get('/admin/empleados/crear', [AdminUserController::class, 'createEmpleado'])->name('admin.empleados.create');
    Route::post('/admin/empleados', [AdminUserController::class, 'storeEmpleado'])->name('admin.empleados.store');
    Route::get('/admin/empleados/{id}/editar', [AdminUserController::class, 'editEmpleado'])->name('admin.empleados.edit');
    Route::put('/admin/empleados/{id}', [AdminUserController::class, 'updateEmpleado'])->name('admin.empleados.update');
    Route::delete('/admin/usuarios/{id}', [AdminUserController::class, 'destroy'])->name('admin.usuarios.destroy');
    Route::post('/admin/empleados/{id}/cambiar-estado', [AdminUserController::class, 'cambiarEstado'])->name('admin.empleados.cambiar-estado');
});

// Rutas de recepcionista con verificación de estado Y rol
Route::prefix('recepcion')->middleware(['auth', 'empleado.activo', 'es.recepcionista'])->group(function () {
    // ... tus rutas de recepcionista (si las tienes)
});

// Rutas para huéspedes (sin verificación de estado de empleado)
Route::middleware(['auth'])->group(function () {
    // Rutas específicas para huéspedes
});