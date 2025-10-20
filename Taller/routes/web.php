<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('/registro', function () {
    return view('Registro');
})->name('registro');

Route::get('/cliente', function () {
    return view('dashboard_Cliente');
})->name('cliente');

Route::get('/gerente', function () {
    return view('dashboard_Gerente');
})->name('gerente');

Route::get('/mecanico', function () {
    return view('dashboard_Mecanico');
})->name('mecanico');

Route::get('/recepcionista', function () {
    return view('dashboard_Recepcionista');
})->name('recepcionista');