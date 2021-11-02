<?php

use App\Http\Controllers\Api\CivilianController;
use App\Http\Controllers\Api\HelperController;
use App\Http\Controllers\Api\OperatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//TODO: RUTAS API PARA LA APP MOVIL DE PARTE DE LOS CIVILES: SOS SCZ
Route::post('/register/civilian', [CivilianController::class, 'register']);
Route::post('/login/civilian', [CivilianController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/civilian', [CivilianController::class, 'civilian']);
    Route::delete('/logout/civilian', [CivilianController::class, 'logout']);
});

//TODO: RUTAS API PARA LA APP MOVIL DE PARTE DE LOS OPERADORES: CCO SOS SCZ
Route::post('/register/operator', [OperatorController::class, 'register']);
Route::post('/login/operator', [OperatorController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/operator', [OperatorController::class, 'operator']);
    Route::delete('/logout/operator', [OperatorController::class, 'logout']);
});

//TODO: RUTAS API PARA LA APP MOVIL DE PARTE DE LOS AUXILIADORES: APOYO SOS SCZ
Route::post('/register/helper', [HelperController::class, 'register']);
Route::post('/login/helper', [HelperController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/helper', [HelperController::class, 'helper']);
    Route::delete('/logout/helper', [HelperController::class, 'logout']);
});
