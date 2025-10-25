<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecepcionistaController;
use App\Http\Controllers\HuespedController;

// Rutas públicas
Route::get('/', function () {
    return view('login');
});

// Rutas de autenticación (las de Breeze)
require __DIR__.'/auth.php';

// Dashboard principal (redirige según rol)
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->esAdministrador()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->esRecepcionista()) {
        return redirect()->route('recepcionista.dashboard');
    } else {
        return redirect()->route('huesped.dashboard');
    }
})->middleware(['auth'])->name('dashboard');

// Rutas de ADMINISTRADOR
Route::prefix('admin')->middleware(['auth', 'es.admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');
});

// Rutas de RECEPCIONISTA
Route::prefix('recepcionista')->middleware(['auth', 'es.recepcionista'])->group(function () {
    Route::get('/dashboard', [RecepcionistaController::class, 'dashboard'])->name('recepcionista.dashboard');
    Route::get('/reservaciones', [RecepcionistaController::class, 'reservaciones'])->name('recepcionista.reservaciones');
    Route::get('/checkin', [RecepcionistaController::class, 'checkin'])->name('recepcionista.checkin');
});

// Rutas de HUÉSPED
Route::prefix('huesped')->middleware(['auth', 'es.huesped'])->group(function () {
    Route::get('/dashboard', [HuespedController::class, 'dashboard'])->name('huesped.dashboard');
    Route::get('/reservar', [HuespedController::class, 'reservar'])->name('huesped.reservar');
    Route::get('/mis-reservaciones', [HuespedController::class, 'misReservaciones'])->name('huesped.mis-reservaciones');
});