<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Emergency;
use App\Models\Helper;
use App\Models\NotificationDevice;
use App\Models\Operator;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OperatorController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'ci' => 'sometimes|string',
            'home_address' => 'sometimes|string',
            'birthday' => 'sometimes|string',
            'sex' => 'required|string',
            'cellphone' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
            'token_name' => 'required|string'
        ]);
        $email = User::where('email', $data['email'])->first();
        if ($email) {
            return response(['message' => 'Error correo ya registrado'],
                401);
        }
        $cellphone = User::where('cellphone', $data['cellphone'])->first();
        if ($cellphone) {
            return response(['message' => 'Error celular ya registrado'],
                402);
        }
        try {
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'name' => $data['name'],
                    'last_name' => $data['last_name'],
                    'ci' => array_key_exists('ci', $data) ? $data['ci'] : null,
                    'home_address' => array_key_exists('home_address', $data) ? $data['home_address'] : null,
                    'birthday' => array_key_exists('birthday', $data) ? $data['birthday'] : null,
                    'sex' => $data['sex'],
                    'cellphone' => $data['cellphone'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'token_name' => $data['token_name'],
                    'type' => 'operator'
                ]);
                $user->markEmailAsVerified();
                Operator::create(['user_id' => $user->id]);
                //$token = $user->createToken($data['token_name'])->plainTextToken;
                return response(['message' => 'Registro finalizado correctamente',
                    'user' => $user], 201);
            });
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'Error registro no completado'],
                406);
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'token_name' => 'required|string',
            'token_firebase' => 'required|string'
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response([
                'message' => 'Correo electronico no encontrado',
            ], 401);
        }
        if (!Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Contraseña incorrecta',
            ], 402);
        }
        if ($user->email_verified_at == null) {
            return response([
                'message' => 'Verifique su correo para poder ingresar',
            ], 403);
        }
        NotificationDevice::create([
            'name_device' => $data['token_name'],
            'token' => $data['token_firebase'],
            'user_id' => $user->id
        ]);
        $token = $user->createToken($data['token_name'])->plainTextToken;
        $user = User::join('operators', 'operators.user_id', 'users.id')
            ->select('users.*', 'operators.id as id_operator')
            ->where('users.id', $user->id)
            ->first();
        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function logout(Request $request)
    {
        $data = $request->validate([
            'token_firebase' => 'required'
        ]);
        NotificationDevice::where('token', $data['token_firebase'])->delete();
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();
            return response(['message' => 'Sesion cerrada'], 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'Error desconocido'], 406);
        }
    }

    public function changeState(Request $request)
    {
        $data = $request->validate([
            'in_turn' => 'required'
        ]);
        $operator = Operator::where('user_id', $request->user()->id)->first();
        $operator->in_turn = !$data['in_turn'];
        $operator->save();
        return response(['message' => 'Estado en turno cambiado'], 200);
    }

    public function assignHelperEmergency(Request $request)
    {
        $data = $request->validate([
            'id_emergency' => 'required',
            'id_helper' => 'required'
        ]);
        try {
            $emergency = Emergency::find($data['id_emergency']);
            if ($emergency && $emergency->state != 'pending') {
                return response(['message' => 'Emergencia ya atendida'], 401);
            }
            $helper = Helper::find($data['id_helper']);
            if ($helper && !$helper->is_free) {
                return response(['message' => 'Rescatista ya está ocupado'], 402);
            }
            $emergency->state = 'progress';
            $helper->is_free = false;
            $emergency->save();
            $helper->save();
            $this->sendEmergency($emergency, $helper);
            return response(['message' => 'Emergencia atendida'], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'Error desconocido'], 500);
        }
    }

    private function sendEmergency(Emergency $emergency, Helper $helper)
    {
        $tokens = NotificationDevice::where('user_id', $helper->user_id)->get();
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization' => 'key=' . env('FCM_RESCATISTA_API_KEY'),
            'Content-Type' => 'application/json'
        ];
        $notification = [
            'title' => $emergency->civilian->user->name . ' tiene una emergencia!',
            'body' => $emergency->description . ' ' . Carbon::now(),
        ];
        $data = [
            'id' => $emergency->id,
            'type' => $emergency->type,
            'description' => $emergency->description,
            'longitude' => $emergency->longitude,
            'latitude' => $emergency->latitude,
            'user' => $emergency->civilian->user,
        ];
        foreach ($tokens as $tok) {
            Http::withHeaders($headers)->post(
                $url . '?=', [
                'to' => $tok->token,
                'priority' => 'high',
                'notification' => $notification,
                'data' => [
                    'message' => 'Notificacion de emergencia',
                    'data' => json_encode($data)
                ]
            ]);
        }
        return $data;
    }

    public function operator(Request $request)
    {
        try {
            return User::join('operators', 'operators.user_id', 'users.id')
                ->select('users.*', 'operators.id as id_operator', 'operators.in_turn')
                ->where('users.id', $request->user()->id)
                ->first();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'Error desconocido'], 406);
        }
    }
}
