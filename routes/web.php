<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsignarController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\informeController;

// Rutas Login
Route::controller(SesionController::class)->group(function () {
    Route::get('/orders/{id}', 'show');
    Route::post('/orders', 'store');
    Route::get('/', 'index')->name('login');
    Route::post('/check', 'check');
    Route::get('/home', 'home')->middleware('auth');
    Route::get('/logout', 'logout');
});

// Rutas Usuarios Adminstrar
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->middleware('auth');
    Route::get('tablaUsers', 'dataTableUser')->name('table.user')->middleware('auth');
    Route::get('selectRol', 'selectRol')->name('select.roles')->middleware('auth');
    Route::post('/createUser', 'create')->middleware('auth');
    Route::get('/user/{id}', 'selectUser')->middleware('auth');
    Route::post('/updateUser', 'update')->middleware('auth');
    Route::get('/statusUser/{id}/{status}', 'status')->middleware('auth');
    Route::get('/deleteUser/{id}', 'delete')->middleware('auth');
});

// Rutas Asignar placas
Route::controller(AsignarController::class)->group(function () {
    Route::get('/asignar', 'index')->middleware('auth');
    Route::get('tablAsignar', 'dataTableAsignar')->name('table.asignar')->middleware('auth');
    Route::get('selectUser', 'selectUsers')->name('select.users')->middleware('auth');
    Route::get('selectPlaca', 'selectPlaca')->name('select.placas')->middleware('auth');
    Route::post('/createAsigne', 'create')->middleware('auth');
    Route::get('/asignar/{id}', 'selectAsigne')->middleware('auth');
    Route::post('/updateAsigne', 'update')->middleware('auth');
    Route::get('/statusAsigne/{id}/{status}', 'status')->middleware('auth');
    Route::get('/deleteAsigne/{id}', 'delete')->middleware('auth');
});

// Rutas Roles Listar
Route::controller(RolController::class)->group(function () {
    Route::get('/roles', 'index')->middleware('auth');
    Route::get('tablaRoles', 'dataTableRol')->name('table.roles')->middleware('auth');
    Route::get('/rol/{id}', 'selectRolId')->middleware('auth');
});

// Rutas Informes a Propietario
Route::controller(informeController::class)->group(function () {
    Route::get('/informes', 'index')->middleware('auth');
    Route::get('/alquiler', 'alquiler')->middleware('auth');
    Route::get('/flete', 'flete')->middleware('auth');
    Route::get('/movimiento', 'movimiento')->middleware('auth');
    Route::get('selectPlacaInforme', 'selectPlacaInforme')->name('select.placasInforme')->middleware('auth');
    Route::post('tablaInformeAlquiler', 'dataTableInfomeAlquiler')->name('table.informeAlquiler')->middleware('auth');
    Route::post('tablaInformeFlete', 'dataTableInfomeFlete')->name('table.informeFlete')->middleware('auth');
    Route::post('tablaInformeMovimiento', 'dataTableInfomeMovimiento')->name('table.informeMovimiento')->middleware('auth');
});
