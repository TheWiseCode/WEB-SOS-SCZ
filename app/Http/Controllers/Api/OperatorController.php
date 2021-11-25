<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\NotificationDevice;
use App\Models\Operator;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                $token = $user->createToken($data['token_name'])->plainTextToken;
                return response(['message' => 'Registro finalizado correctamente',
                    'user' => $user, 'token' => $token], 201);
            });
        } catch (Exception $e) {
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
            return response(['message' => 'Error desconocido'], 406);
        }
    }

    public function operator(Request $request)
    {
        try {
            return User::join('operators', 'operators.user_id', 'users.id')
                ->select('users.*', 'operators.id as id_operator')
                ->where('users.id', $request->user()->id)
                ->first();
        } catch (Exception $e) {
            return response(['message' => 'Error desconocido'], 406);
        }
    }
}
