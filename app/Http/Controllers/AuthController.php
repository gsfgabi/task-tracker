<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Collaborator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $collaborator = Collaborator::where('username', $request->username)->first();

        if (!$collaborator || !Hash::check($request->password, $collaborator->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        $token = JWTAuth::fromUser($collaborator);

        return response()->json([
            'status' => 'success',
            'message' => 'Login realizado com sucesso',
            'collaborator' => $collaborator,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Logout realizado com sucesso',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'collaborator' => Auth::user(),
            'authorization' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'collaborator' => Auth::user(),
        ]);
    }
}