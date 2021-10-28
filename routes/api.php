<?php

use App\Http\Controllers\Api\SessionController;
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

Route::post('/register/user', [SessionController::class, 'register']);
Route::post('/login', [SessionController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [SessionController::class, 'user']);
    Route::delete('/logout', [SessionController::class, 'logout']);
});
