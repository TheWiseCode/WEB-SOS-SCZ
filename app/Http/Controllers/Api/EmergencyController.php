<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Civilian;
use App\Models\Emergency;
use App\Models\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class EmergencyController extends Controller
{
    public function sendEmergency(Emergency $emergency)
    {
        try {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $headers = [
                'Authorization' => 'key=' . env('FCM_OPERATOR_API_KEY'),
                'Content-Type' => 'application/json'
            ];
            $notification = [
                'title' => $emergency->civilian->user->name . ' tiene una emergencia!',
                'body' => $emergency->description . ' ' . Carbon::now(),
            ];
            $lat = $emergency->latitude;
            $lon = $emergency->longitude;
            $type = $emergency->type;
            $query = "select a.id, b.name, b.last_name, b.cellphone,
                    a.rank, a.longitude, a.latitude, a.user_id
                from helpers a inner join users b on a.user_id = b.id
                where a.in_turn = true and a.is_free = true and a.longitude is not null and a.latitude is not null
                    and a.type = '{$type}' and distance({$lat},{$lon}, a.latitude, a.longitude) < 5";
            $helpers = DB::select($query);
            if (count($helpers) < 2) {
                $fact = [1, 1, 1, -1, -1, 1, -1, -1];
                $au = [0.03, 0.04];
                $au1 = [0.05, 0.07];
                $helpers = Helper::where('type', $type)->take(8)->get();
                for ($i = 0; $i < 8 && $helpers[$i] != null; $i += 2) {
                    $nlat = $lat + $au[0] * $fact[$i];
                    $nlon = $lon + $au[1] * $fact[$i + 1];
                    $helpers[$i]->latitude = $nlat;
                    $helpers[$i]->longitude = $nlon;
                    $nlat = $lat + $au1[0] * $fact[$i];
                    $nlon = $lon + $au1[1] * $fact[$i + 1];
                    $helpers[$i + 1]->latitude = $nlat;
                    $helpers[$i + 1]->longitude = $nlon;
                }
            }
            $data = [
                'id' => $emergency->id,
                'user' => $emergency->civilian->user,
                'type' => $emergency->type,
                'longitude' => $emergency->longitude,
                'latitude' => $emergency->latitude,
                'helpers' => $helpers
            ];
            return Http::withHeaders($headers)->post(
                $url . '?=', [
                'to' => '/topics/in_turn_operators',
                'priority' => 'high',
                'notification' => $notification,
                'data' => $data
            ]);
        }catch (\Exception $e){
            return response(['error' => $e], 500);
        }
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

}
