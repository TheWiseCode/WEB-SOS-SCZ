<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmergencyRequest;
use App\Models\Civilian;
use App\Models\Emergency;
use App\Models\Operator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;

class EmergencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * @param String $
     */
    public function sendNotification(Emergency $emergency){

        $url = 'https://fcm.googleapis.com/fcm/send';
        $dataArr = array(
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'id' => $emergency->id,
            //'status'=>'done',
            'Longitude' => $emergency->longitude,
            'Latitude' => $emergency->latitude,
        );
        $notification = array(
            'title' => $emergency->civilian->user->name . ' esta en peligro!',
            'body' => $emergency->description . ' ' . Carbon::now(),
            //'image'=> $req->img,
            'sound' => 'default',
            'badge' => '1',
        );
        $arrayToSend = array(
//                'to' => 'fpLlqRvtR0maMLD36uLUyK:APA91bFuTXUKhN8PcP5_b7eU-SZnWKk5ugAaOAJB1eFEX6nLUehURoll_0UVSmr0-Mvcz7VJtcBM71122jpPj9FzMM2G7UIK80Rzcc9T8M0b2cZIOyGCPDPJsu1HfmWMelfidB6hx4fg', //podria ser NotificationDevide::all()->pluck('id');
            'to' => '/topics/in_turn_operators',
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
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmergencyRequest $request)
    {
        $citizen = Civilian::find($request->civilian_id);
        //$type = type_institution::find($request->type_institution_id);
        if( !$citizen ){
            abort(404,'Some object not found');
        }

        $new_emergency = Emergency::create($request->validated());
        $responde = $this->sendNotification($new_emergency);

        return $responde;
        /*
        //Devuelve el token de los operadores actualmente en turno (in_turn = true)
        $in_turn_operators = Operator::where('in_turn','=',false)->pluck('user_id');
        $items = collect([]);
        foreach ($in_turn_operators as $op){
//            array_push($items,\App\Models\NotificationDevice::where('user_id','=',$op)->pluck('token'));
            $items->push(\App\Models\NotificationDevice::where('user_id','=',$op)->pluck('token'));
        }

        $ready_token = (String)json_encode($items->pop());

        */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
