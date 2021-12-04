<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/helpers',function (){
    $helpers = \App\Models\Helper::all()->pluck('id');
    //$user_id_collection = $users->where('type', '=', '3')->sortDesc()->pluck('id');
    return $helpers;
});
