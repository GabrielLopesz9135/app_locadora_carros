<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

 Route::prefix('v1')->middleware('auth:api')->group(function(){
    Route::apiResource('/carros', CarroController::class);
    Route::apiResource('/modelos', ModeloController::class);
    Route::apiResource('/clientes', ClienteController::class);
    Route::apiResource('/locacoes', LocacaoController::class);
    Route::apiResource('/marcas', MarcaController::class);
}); 



Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

/* 
Route::post('/login', [AuthController::class, 'login'])->name('login'); */
