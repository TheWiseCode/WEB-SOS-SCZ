<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

//use App\Models\NotificationDevice;
use App\Models\Civilian;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CivilianController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'last_name' => 'required|string',
            'ci' => 'required|string',
            'home_address' => 'required|string',
            'birthday' => 'required|string',
            'sex' => 'required|string',
            'cellphone' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
            'token_name' => 'string',
            'type' => 'string'
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
                    'ci' => $data['ci'],
                    'home_address' => $data['home_address'],
                    'birthday' => $data['birthday'],
                    'sex' => $data['sex'],
                    'cellphone' => $data['cellphone'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'token_name' => $data['token_name'],
                    'type' => 'civilian'
                ]);
                $user->markEmailAsVerified();
                Civilian::create(['user_id' => $user->id]);
                $token = $user->createToken($data['token_name'])->plainTextToken;
                return response(['user' => $user, 'token' => $token], 201);
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
            //'token_firebase' => 'required|string'
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
        $token = $user->createToken($data['token_name'])->plainTextToken;
        $user = User::join('civilians', 'civilians.user_id', 'users.id')
            ->select('users.*', 'civilians.id')
            ->where('users.id', $user->id)
            ->first();
        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function logout(Request $request)
    {
        /*$data = $request->validate([
            'token_firebase' => 'required'
        ]);*/
        //NotificationDevice::where('token', $data['token_firebase'])->delete();
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();
            return response(['message' => 'Sesion cerrada'], 200);
        }catch (Exception $e){
            return response(['message' => 'Error desconocido'], 406);
        }
    }

    public function civilian(Request $request)
    {
        try {
            return User::join('civilians', 'civilians.user_id', 'users.id')
                ->select('users.*', 'civilians.id')
                ->where('users.id', $request->user()->id)
                ->first();
        } catch (Exception $e) {
            return response(['message' => 'Error desconocido'], 406);
        }
    }
}
