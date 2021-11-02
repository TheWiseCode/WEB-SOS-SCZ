<?php

use App\Http\Controllers\Api\CivilianController;
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

Route::post('/register/civilian', [CivilianController::class, 'register']);
Route::post('/login/civilian', [CivilianController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/civilian', [CivilianController::class, 'civilian']);
    Route::delete('/logout/civilian', [CivilianController::class, 'logout']);
});
