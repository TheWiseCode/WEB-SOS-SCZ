<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Helper;
use App\Models\NotificationDevice;
use App\Models\User;
use App\Models\WorkShift;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class HelperController extends Controller
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
            'type_helper' => 'required|string',
            'rank' => 'sometimes|string',
            'emergency_unit' => 'required|string',
            'start_turn' => 'required|string',
            'end_turn' => 'required|string',
            'workdays' => 'required|array|min:1',
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
                    'type' => 'helper'
                ]);
                $user->markEmailAsVerified();
                $helper = Helper::create([
                    'user_id' => $user->id,
                    'type' => $data['type_helper'],
                    'rank' => array_key_exists('rank', $data) ? $data['rank'] : null,
                    //'emergency_unit' => $data['emergency_unit']
                ]);
                for ($i = 0; $i < count($data['workdays']); $i++) {
                    WorkShift::create([
                        'day_turn' => $data['workdays'][$i],
                        'start_turn' => $data['start_turn'],
                        'end_turn' => $data['end_turn'],
                        'helper_id' => $helper->id
                    ]);
                }
                //$token = $user->createToken($data['token_name'])->plainTextToken;
                return response(['message' => 'Registro finalizado correctamente',
                    'user' => $user], 201);
            });
        } catch (Exception $e) {
            Log::debug($e->getMessage(), $e->getTrace());
            return response(['message' => 'Error registro no completado ' . $e->getMessage()],
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
                'message' => 'Contrase??a incorrecta',
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
        $user = User::join('helpers', 'helpers.user_id', 'users.id')
            ->select('users.*', 'helpers.id as id_helper', 'helpers.type as type_helper',
                'helpers.rank', 'helpers.in_turn'
            )
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
            Log::debug($e->getMessage(), $e->getTrace());
            return response(['message' => 'Error desconocido'], 406);
        }
    }

    public function changeState(Request $request)
    {
        try {
            $data = $request->validate([
                'in_turn' => 'required'
            ]);
            $helper = Helper::where('user_id', $request->user()->id)->first();
            $helper->in_turn = !$data['in_turn'];
            $helper->save();
            return response(['message' => 'Estado en turno cambiado'], 200);
        } catch (Exception $e) {
            Log::debug($e->getMessage(), $e->getTrace());
            return response(500);
        }
    }

    public function helper(Request $request)
    {
        try {
            $user = User::join('helpers', 'helpers.user_id', 'users.id')
                ->select('users.*', 'helpers.id as id_helper', 'helpers.type as type_helper',
                    'helpers.rank', 'helpers.in_turn'
                //,'helpers.start_turn', 'helpers.end_turn'
                )
                ->where('users.id', $request->user()->id)->first();
            $workdays = WorkShift::where('helper_id', $user->id_helper)->get()->toArray();
            return [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'ci' => $user->ci,
                'home_address' => $user->home_address,
                'birthday' => $user->birthday,
                'sex' => $user->sex,
                'cellphone' => $user->cellphone,
                'email' => $user->email,
                'id_helper' => $user->id_helper,
                'type_helper' => $user->type_helper,
                'rank' => $user->rank,
                //'emergency_unit' => $user->emergency_unit,
                'emergency_unit' => 'falta',
                'in_turn' => $user->in_turn,
                'start_turn' => $workdays[0]['start_turn'],
                'end_turn' => $workdays[0]['end_turn'],
                'workdays' => $workdays
            ];
        } catch (Exception $e) {
            Log::debug($e->getMessage(), $e->getTrace());
            return response(['message' => 'Error desconocido', 'error' => $e->getMessage()], 406);
        }
    }

    public function updateLocation(Request $request)
    {
        $data = $request->validate([
            'longitude' => 'required',
            'latitude' => 'required'
        ]);
        try {
            $helper = Helper::where('user_id', $request->user()->id)->first();
            if ($helper) {
                $helper->latitude = $data['latitude'];
                $helper->longitude = $data['longitude'];
                $helper->save();
                return response(['message' => 'Ubicacion del rescatista actualizada'], 200);
            }
            return response(['message' => 'Rescatista no encontrado'], 400);
        } catch (Exception $e) {
            Log::debug($e->getMessage(), $e->getTrace());
            return response(['error' => $e->getMessage()], 500);
        }
    }
}
