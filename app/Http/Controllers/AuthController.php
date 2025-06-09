<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Показати форму логіну
    public function showLogin(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route('catalog');
        }
        return view('auth.login');
    }

    // Обробка логіну
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = DB::table('users')->where('email', $data['email'])->first();

        if ($user && Hash::check($data['password'], $user->password)) {
            $request->session()->put('user_id', $user->id);
            $request->session()->regenerate();
            return redirect()->intended(route('catalog'));
        }

        return back()
            ->with('error', 'Невірний email або пароль.')
            ->withInput(['email' => $data['email']]);
    }

    // Показати форму реєстрації
    public function showRegister(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    // Обробка реєстрації
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $id = DB::table('users')->insertGetId([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request->session()->put('user_id', $id);
        $request->session()->regenerate();

        return redirect()->route('catalog');
    }

    // Вихід
    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
