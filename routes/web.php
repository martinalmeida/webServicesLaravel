<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\UserController;

// Rutas Login
Route::get('/', [SesionController::class, 'index']);
Route::post('/check', [SesionController::class, 'check']);
Route::get('/home', [SesionController::class, 'home'])->middleware('auth');
Route::get('/logout', [SesionController::class, 'logout']);

// Rutas Usuarios Adminstrar
Route::get('/users', [UserController::class, 'index'])->middleware('auth');
