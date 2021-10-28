<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
//use App\Models\NotificationDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Exception;

class SessionController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
            'token_name' => 'string'
        ]);
        $email = User::where('email', $data['email'])->first();
        if ($email) {
            return response(['message' => 'Error correo ya registrado'],
                406);
        }
        try {
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
                $token = $user->createToken($data['token_name'])->plainTextToken;
                return response(['user' => $user, 'token' => $token], 201);
            });
        }catch (Exception $e) {
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
            ], 401);
        }
        if ($user->email_verified_at == null) {
            return response([
                'message' => 'Verifique su correo para poder ingresar',
            ], 402);
        }
        $token = $user->createToken($data['token_name'])->plainTextToken;
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
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return [
            'message' => 'Sesion cerrada'
        ];
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
