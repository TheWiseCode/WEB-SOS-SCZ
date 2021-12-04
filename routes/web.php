<?php

use App\Models\Operator;
use App\Models\User;
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


Route::get('/operators', function (){
//    $in_turn_operators = Operator::where('in_turn','=',true)->pluck('user_id')->sortDesc();
    $in_turn_operators = Operator::where('in_turn','=',true)->pluck('user_id');
    $items = collect([]);
    foreach ($in_turn_operators as $op){
        $items->push(\App\Models\NotificationDevice::where('user_id','=',$op)->pluck('token'));
//        array_push($items,\App\Models\NotificationDevice::where('user_id','=',$op)->get('token'));
    }
//    return (String)array_pop($items);
        return (String)json_encode($items->pop());
});

Route::get('/sendNotification', function(){
    $url = 'https://fcm.googleapis.com/fcm/send';
    $dataArr = array(
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        'id' => 2,
        'status'=>"done",
        'Longitude' => -30.123465,
        'Latitude' => 45.123465,
    );
    $notification = array(
        'title' => "Nueva Emergencia! Go Diego Go!",
        'text' => "Texto de notificacion by Faraon Love Shady",
        //'image'=> $req->img,
        'sound' => 'default',
        'badge' => '1',
    );
    $arrayToSend = array(
        'to' => 'fpLlqRvtR0maMLD36uLUyK:APA91bFuTXUKhN8PcP5_b7eU-SZnWKk5ugAaOAJB1eFEX6nLUehURoll_0UVSmr0-Mvcz7VJtcBM71122jpPj9FzMM2G7UIK80Rzcc9T8M0b2cZIOyGCPDPJsu1HfmWMelfidB6hx4fg', //podria ser NotificationDevide::all()->pluck('id');
//        'to' => "/topics/all",
        'notification' => $notification,
        'data' => $dataArr,
        'priority'=>'high'
    );
    $fields = json_encode($arrayToSend);
    $headers = array(
        'Authorization: key=' . "AAAA5kWrVzo:APA91bFyafq8rsMSU0yvHsSSrMpjK1--rdcnVAy9mPfK8gZyCWXcCxc5SwlKIunZlNNaaeoqrmLsACOn04DANRkpkHpbicInRqaY30f2jFt71dMzE5jK4CxJGPJ8JqFcQrAWDgktYvAz",
        'Content-Type: application/json',
        "accept: */*"
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );

    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    $response = curl_exec ( $ch );

    curl_close ( $ch );

    return $response;

});

