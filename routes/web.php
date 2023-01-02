<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsignarController;
use App\Http\Controllers\RolController;

// Rutas Login
Route::get('/', [SesionController::class, 'index'])->name('login');
Route::post('/check', [SesionController::class, 'check']);
Route::get('/home', [SesionController::class, 'home'])->middleware('auth');
Route::get('/logout', [SesionController::class, 'logout']);

// Rutas Usuarios Adminstrar
Route::get('/users', [UserController::class, 'index'])->middleware('auth');
Route::get('tablaUsers', [UserController::class, 'dataTableUser'])->name('table.user')->middleware('auth');
Route::get('/status/{id}/{status}', [UserController::class, 'status'])->middleware('auth');

// Rutas Asignar placas
Route::get('/asignar', [AsignarController::class, 'index'])->middleware('auth');
Route::get('tablAsignar', [AsignarController::class, 'dataTableAsignar'])->name('table.asignar')->middleware('auth');

// Rutas Roles Listar
Route::get('/roles', [RolController::class, 'index'])->middleware('auth');
Route::get('tablaRoles', [RolController::class, 'dataTableRol'])->name('table.roles')->middleware('auth');
Route::get('/selectRol', [RolController::class, 'selectRol'])->name('select.roles')->middleware('auth');
