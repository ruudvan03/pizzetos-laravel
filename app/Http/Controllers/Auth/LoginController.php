<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nickName' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Intentamos autenticar con nickName y validamos que el empleado esté activo (status = 1)
        if (Auth::attempt(['nickName' => $credentials['nickName'], 'password' => $credentials['password'], 'status' => 1])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'nickName' => 'Las credenciales no coinciden o el usuario está inactivo.',
        ])->onlyInput('nickName');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}