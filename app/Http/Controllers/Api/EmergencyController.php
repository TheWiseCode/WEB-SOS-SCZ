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
use Illuminate\Support\Facades\Http;
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

    public function sendEmergency(Emergency $emergency)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization' => 'key=' . env('FCM_OPERATOR_API_KEY'),
            'Content-Type' => 'application/json'
        ];
        $notification = [
            'title' => $emergency->civilian->user->name . ' tiene una emergencia!',
            'body' => $emergency->description . ' ' . Carbon::now(),
        ];
        $data = [
            'id' => $emergency->id,
            'user' => $emergency->civilian->user,
            'type' => $emergency->type,
            'longitude' => $emergency->longitude,
            'latitude' => $emergency->latitude,
        ];
        return Http::withHeaders($headers)->post(
            $url . '?=', [
            'to' => '/topics/in_turn_operators',
            'priority' => 'high',
            'notification' => $notification,
            'data' => $data
        ]);
    }

    public function requestEmergency(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'description' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        $civilian = Civilian::where('user_id', $request->user()->id)->first();
        $emergency = Emergency::create([
            'type' => $data['type'],
            'description' => $data['description'],
            'longitude' => $data['longitude'],
            'latitude' => $data['latitude'],
            'civilian_id' => $civilian->id
        ]);
        $response = $this->sendEmergency($emergency);
        return response($response, 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmergencyRequest $request)
    {
        $citizen = Civilian::find($request->civilian_id);
        //$type = type_institution::find($request->type_institution_id);
        if (!$citizen) {
            abort(404, 'Some object not found');
        }

        $new_emergency = Emergency::create($request->validated());
        $responde = $this->sendEmergency($new_emergency);

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
