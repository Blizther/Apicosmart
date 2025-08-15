<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    public function handle($request, Closure $next, $rol)
    {
        if (!Auth::check() || Auth::user()->rol !== $rol) {
            abort(403, 'No tienes permiso para acceder.');
        }

        return $next($request);
    }
}
