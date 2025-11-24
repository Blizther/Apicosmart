<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('nombreUsuario', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            //return redirect()->intended('dashboard');
            //redireccionamos segun el rol
            // Redireccionar según el rol
            if (Auth::user()->rol === 'administrador')
            {
                return redirect()->intended('/administrador/inicio');
            }
            if (Auth::user()->rol === 'usuario')
            {
                return redirect()->intended('/usuario/inicio');
            }
            if (Auth::user()->rol === 'colaborador')
            {
                return redirect()->intended('/usuario/inicio');
            }
        }

        return back()->withErrors(['nombreUsuario' => 'Usuario y/o Contraseña inválidas'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}