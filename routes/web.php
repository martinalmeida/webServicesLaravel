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
Route::get('selectRol', [UserController::class, 'selectRol'])->name('select.roles')->middleware('auth');
Route::post('/createUser', [UserController::class, 'create'])->middleware('auth');
Route::get('/user/{id}', [UserController::class, 'selectUser'])->middleware('auth');
Route::post('/updateUser', [UserController::class, 'update'])->middleware('auth');
Route::get('/statusUser/{id}/{status}', [UserController::class, 'status'])->middleware('auth');
Route::get('/deleteUser/{id}', [UserController::class, 'delete'])->middleware('auth');

// Rutas Asignar placas
Route::get('/asignar', [AsignarController::class, 'index'])->middleware('auth');
Route::get('tablAsignar', [AsignarController::class, 'dataTableAsignar'])->name('table.asignar')->middleware('auth');
Route::get('selectUser', [AsignarController::class, 'selectUsers'])->name('select.users')->middleware('auth');
Route::get('selectPlaca', [AsignarController::class, 'selectPlaca'])->name('select.placas')->middleware('auth');
Route::post('/createAsigne', [AsignarController::class, 'create'])->middleware('auth');
Route::get('/asignar/{id}', [AsignarController::class, 'selectAsigne'])->middleware('auth');
Route::post('/updateAsigne', [AsignarController::class, 'update'])->middleware('auth');
Route::get('/statusAsigne/{id}/{status}', [AsignarController::class, 'status'])->middleware('auth');
Route::get('/deleteAsigne/{id}', [AsignarController::class, 'delete'])->middleware('auth');

// Rutas Roles Listar
Route::get('/roles', [RolController::class, 'index'])->middleware('auth');
Route::get('tablaRoles', [RolController::class, 'dataTableRol'])->name('table.roles')->middleware('auth');
Route::get('/rol/{id}', [RolController::class, 'selectRolId'])->middleware('auth');
