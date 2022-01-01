<?php

use App\Http\Controllers\Api\CivilianController;
use App\Http\Controllers\Api\EmergencyController;
use App\Http\Controllers\Api\HelperController;
use App\Http\Controllers\Api\OperatorController;
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
Route::prefix('civilian')->group(function () {
    Route::post('/register', [CivilianController::class, 'register']);
    Route::post('/login', [CivilianController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('', [CivilianController::class, 'civilian']);
        Route::delete('/logout', [CivilianController::class, 'logout']);
        Route::post('/request-emergency', [EmergencyController::class, 'requestEmergency']);
    });
});

//TODO: RUTAS API PARA LA APP MOVIL DE PARTE DE LOS OPERADORES: CCO SOS SCZ
Route::prefix('operator')->group(function () {
    Route::post('/register', [OperatorController::class, 'register']);
    Route::post('/login', [OperatorController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/assign-helper-emergency', [OperatorController::class, 'assignHelperEmergency']);
        Route::get('', [OperatorController::class, 'operator']);
        Route::post('/change-state', [OperatorController::class, 'changeState']);
        Route::delete('/logout', [OperatorController::class, 'logout']);
    });
});

//TODO: RUTAS API PARA LA APP MOVIL DE PARTE DE LOS AUXILIADORES: APOYO SOS SCZ
Route::prefix('helper')->group(function () {
    Route::post('/register', [HelperController::class, 'register']);
    Route::post('/login', [HelperController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/update-location', [HelperController::class, 'updateLocation']);
        Route::get('', [HelperController::class, 'helper']);
        Route::post('/change-state', [OperatorController::class, 'changeState']);
        Route::delete('/logout', [HelperController::class, 'logout']);
    });
});
