<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Пропускає далі тільки admin-користувачів.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Користувач не залогінений або його роль ≠ 'admin' → 403
        if (! $request->user() || $request->user()->role !== 'admin') {
            abort(403, 'Access denied');
        }

        // Інакше – пропускаємо до контролера
        return $next($request);
    }
}
