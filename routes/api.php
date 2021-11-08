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


Route::apiResource('/type-institutions',\App\Http\Controllers\Api\TypeIntitutionController::class);
Route::apiResource('/type-vehicles', \App\Http\Controllers\Api\TypeVehicleController::class);
Route::apiResource('/schedules', \App\Http\Controllers\Api\ScheduleController::class);
Route::apiResource('/emergencies',\App\Http\Controllers\Api\EmergencyController::class);
Route::apiResource('/citizens',\App\Http\Controllers\Api\CitizenController::class);
Route::apiResource('/institutions',\App\Http\Controllers\Api\InstitutionController::class);
Route::get('institutions-positions',[\App\Http\Controllers\Api\InstitutionController::class,'InstPositions'])->name('getPositionsAtInstitutes');
Route::apiResource('/positions',\App\Http\Controllers\Api\PositionsController::class);
Route::apiResource('/officer', \App\Http\Controllers\Api\OfficerController::class);
Route::get('/officer-workshifts/{id}',[\App\Http\Controllers\Api\OfficerController::class,'getWorkShifts'])->name('getWorkShifts');
Route::apiResource('/vehicles',\App\Http\Controllers\Api\VehicleController::class);
Route::post('/work-shifts-coordinates/{id}',[\App\Http\Controllers\Api\WorkShiftController::class,'getLocations']);
Route::apiResource('/work-shifts',\App\Http\Controllers\Api\WorkShiftController::class);
Route::apiResource('/work-shift-locations', \App\Http\Controllers\Api\WorkShiftLocationController::class);
