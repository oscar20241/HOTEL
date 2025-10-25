<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');


Route::get('/Gerente', function () {
    return view('Gerente');
})->name('Gerente');

Route::get('/Recepcionista', function () {
    return view('Recepcionista');
})->name('Recepcionista');

Route::get('/Registro', function () {
    return view('Registro');
})->name('Registro');

route::get('/Huesped', function () {
    return view('Huesped');
})->name('Huesped');