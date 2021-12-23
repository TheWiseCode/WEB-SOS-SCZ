<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmergencyRequest;
use App\Models\Civilian;
use App\Models\Emergency;
use App\Models\Helper;
use App\Models\Operator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\TextUI\Help;
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

    public function sendEmergencyToInTurnOperators(Emergency $emergency)
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
        $response = $this->sendEmergencyToInTurnOperators($emergency);
        return response($response, 201);
    }

    public function getEmergenciesByOperator(Request $request){
        $data = $request->validate([
            'operator_id' => 'required'
        ]);
        $operator = Operator::find($request->operator_id);
        if(!$operator){
            abort(404, 'Some object not found');
        }
        $emergency_list =  Emergency::where('operator_id', $request->operator_id)->get();
        return response($emergency_list, 200);
    }

    public function getEmergenciesByCivil(Request $request){
        $data = $request->validate([
            'civilian_id' => 'required'
        ]);
        $operator = Civilian::find($request->civilian_id);
        if(!$operator){
            abort(404, 'Some object not found');
        }
        $emergency_list =  Emergency::where('operator_id', $request->civilian_id)->get();
        return response($emergency_list, 200);
    }

    public function getEmergenciesByHelper(Request $request){
        $data = $request->validate([
            'civilian_id' => 'required'
        ]);
        $operator = Helper::find($request->helper_id);
        if(!$operator){
            abort(404, 'Some object not found');
        }
        $emergency_list =  Emergency::where('helper_id', $request->helper_id)->get();
        return response($emergency_list, 200);
    }

    public function ViewNewEmergencies(){
        $new_emergencies = Emergency::where('status',1)->get();
        return response($new_emergencies, 200);
    }

    /**
     * @param Request $request
     * This method es invocado cuando una emergencia es seleccionada por un operador. Actualiza el campo operator en la
     * tabla emergencies
     * Cuando una emergencia es aceptada, el operador podra ver la lista de rescatistas disponibles segun el tipo
     */
    public function EmergencyAcceptedByOperator(Request $request){
        $request->validate([
            'operator_id' => 'required',
            'emergency_id' => 'required'
        ]);

        $operator = Operator::where('id', $request->operator_id)->get();
        $emergency = Emergency::where('id', $request->emergency_id)->get();

        if(!$operator || !$emergency){
            abort(404, 'Some object not found');
        }

        $emergency->update([
            'operator_id' => $request->operator_id,
            'status' => '2' //en curso
        ]);
        return response(200);
    }

    /**
     * @param Request $request
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     * Cuando el operador seleccione un rescatista se va actualizar la tabla emergencies y al mismo tiempo se le va
     * enviar una notificacion a todos los dispositivos
     */

    public function EmergencyAssignedToHelper(Request $request){
        $request->validate([
            'helper_id' => 'required',
            'emergency_id' => 'required'
        ]);

        $helper = Helper::where('id', $request->helper_id)->get();
        $emergency = Emergency::where('id', $request->emergency_id)->get();
        $is_free = $helper->is_free;

        if(!$helper || !$emergency || !$is_free){
            abort(404, 'Some object not found');
        }

        $emergency->update([
            'helper_id' => $request->helper_id,
        ]);

        $helper_userId =  $helper->user_id;
        return $this->sendNotificationToHelper($helper_userId, $emergency);
    }

    public function sendNotificationToHelper($helper_userId,Emergency $emergency){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization' => 'key=' . env('FCM_OPERATOR_API_KEY'),
            'Content-Type' => 'application/json'
        ];
        $notification = [
            'title' => 'Te han asignado una emergencia!',
            'body' => $emergency->civilian->user->name , ' esta en problemas ' . Carbon::now(),
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
            'to' => '/topics/in_turn_helpers/' . $helper_userId, //TODO: QUE CADA RESCATISTA SE SUBCRIBA A UN CANAL CON SU ID EN EL FRONT
            'priority' => 'high',
            'notification' => $notification,
            'data' => $data
        ]);
    }

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
    }
}
