<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Collaborator;

class WebAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        Log::info('Tentativa de login', [
            'username' => $request->username,
            'ip' => $request->ip()
        ]);

        $collaborator = Collaborator::where('username', $request->username)->first();

        if (!$collaborator) {
            Log::warning('Usuário não encontrado', ['username' => $request->username]);
            return back()->withErrors([
                'username' => 'Credenciais inválidas.',
            ])->withInput($request->only('username'));
        }

        if (!Hash::check($request->password, $collaborator->password)) {
            Log::warning('Senha incorreta', ['username' => $request->username]);
            return back()->withErrors([
                'username' => 'Credenciais inválidas.',
            ])->withInput($request->only('username'));
        }

        Log::info('Login bem-sucedido', ['username' => $request->username]);

        // Log in the collaborator
        Auth::login($collaborator);

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
} 