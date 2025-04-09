<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Seguimientos;
use App\Http\Controllers\StockController;
use App\Http\Controllers\Usuarios;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('inicio');
 });


//RUTA RESULTADO DE BUSQUEDA 
Route::get('/stock/{symbol}', [StockController::class, 'show']);

//RUTA A LOGUEARSE
Route::get('login', [StockController::class,'login'])->name('login'); //se tiene que llamar asi el alias si o si para que la encuentre lVL
Route::post('login', [LoginController::class,'login']);

Route::get('iniciouser', [StockController::class,'iniciouser'])->middleware('auth');

//RUTA DESLOGUEARSE
Route::get('logout',[LoginController::class,'logout']);

//RUTA REGISTRARSE
Route::get('registration', [StockController::class,'registration']);
Route::post('registrousuario', [Usuarios::class,'registrarusuario']);

//RUTA SEGUIR ACCION
Route::post('/seguiraccion', [Seguimientos::class, 'iniciarseguimiento'])->middleware('auth'); //VERIFICA SI EL USUARIO ESTÁ LOGUEADO ANTES DE SEGUIR LA RUTA
Route::get('verseguimiento/{symbol}', [Seguimientos::class,'verseguimiento']);

//RUTA ELIMINAR ACCION
Route::delete('accion/{id}', [Seguimientos::class, 'destroy']);