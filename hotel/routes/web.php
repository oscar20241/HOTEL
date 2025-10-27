<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecepcionistaController;
use App\Http\Controllers\HuespedController;
use App\Http\Controllers\Auth\RegisterController;


Route::get('/registro', function () {
    return view('Registro');
})->name('registro');
// Ruta PRINCIPAL - funciona para ambos: login y dashboard
Route::get('/', function () {
    // Si está autenticado, mostrar dashboard según rol
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->esAdministrador()) {
            return view('Gerente');
        } elseif ($user->esRecepcionista()) {
            return view('Recepcionista');
        } else {
            return view('Huesped');
        }
    }
    
    // Si no está autenticado, mostrar login
    return view('login');
});

// Rutas de autenticación (Breeze)
require __DIR__.'/auth.php';

// La ruta /dashboard puede redirigir a /
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Tus otras rutas de admin, recepcionista, huésped...
Route::prefix('admin')->middleware(['auth', 'es.admin'])->group(function () {
    Route::get('/empleados', [AdminController::class, 'empleados'])->name('admin.empleados');
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');
});

// ... resto de tus rutas

// routes/web.php
use App\Http\Controllers\RegistroController;

// Rutas de registro
Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');