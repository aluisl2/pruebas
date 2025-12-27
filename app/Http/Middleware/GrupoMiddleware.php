<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class GrupoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // conseguimos los grupos del usuario actual
        $user = Auth::user();
        $grupos = $user->grupos;
        Inertia::share([
            'grupos' => $grupos,
        ]);
        return $next($request);
    }
}
