<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;                 // ← додали
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    /**
     * Якщо в сесії немає user_id → редірект на /login.
     * Якщо є — залогуємо користувача через Auth::loginUsingId()
     * і пропустимо далі.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Немає user_id у сесії → на логін
        if (! $request->session()->has('user_id')) {
            return redirect()->route('login');
        }

        // 2. Є user_id, але в Auth ще ніхто не залогінений
        if (! Auth::check()) {
            Auth::loginUsingId($request->session()->get('user_id'));
            // тепер Auth::user() повертає модель User
        }

        // 3. Далі все, як у звичайному Laravel
        return $next($request);
    }
}
