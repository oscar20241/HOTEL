<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

route::get('/home', function () {
    return view('home');
})->name('home');

route::get('/registro', function () {
    return view('registro');
})->name('registro');

route::get('/login', function () {
    return view('login');
})->name('login');

route::get('/cliente', function () {
    return view('contacto');
})->name('contacto');

route::get('/gerente', function () {
    return view('gerente');
})->name('gerente');

route::get('/mecanico', function () {
    return view('mecanico');
})->name('mecanico');

route::get('/recepcionista', function () {
    return view('recepcionista');
})->name('recepcionista');